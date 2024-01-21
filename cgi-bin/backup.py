import os
import sqlite3
import time
import requests
import logging
from datetime import datetime

from urllib3.exceptions import InsecureRequestWarning

# variabili globali
delete_corrupted_file = True # elimina i file ritenuti non validi

# Ignora i warning degli SSL non validi
requests.packages.urllib3.disable_warnings(category=InsecureRequestWarning)

# Crea la cartella logs se non esiste
logs_dir = '/var/www/logs'
if not os.path.exists(logs_dir):
    os.mkdir(logs_dir)

# Crea un file di log con il timestamp corrente per ogni sessione di backup
now = datetime.now()
log_file = f'{logs_dir}/backup_{now.strftime("%Y-%m-%d_%H-%M-%S")}.log'

# Configura il logger per scrivere in un file specifico
logger = logging.getLogger()
logger.setLevel(logging.DEBUG)

log_file_global = f'{logs_dir}/backup_global.log'
file_handler = logging.FileHandler(log_file_global)
file_handler.setLevel(logging.DEBUG)

# Formato di output del log
formatter = logging.Formatter('%(asctime)s - %(levelname)s - %(message)s')
file_handler.setFormatter(formatter)

logger.addHandler(file_handler)

with open(log_file, 'w') as f:
    f.write(f'Inizio sessione di backup alle {now.strftime("%Y-%m-%d %H:%M:%S")}\n')
    logger.info(f'Inizio sessione di backup alle {now.strftime("%Y-%m-%d %H:%M:%S")}')

def welcome():
    print('==================================================================')
    print('Benvenuto nello script fantastico di backup automatizato FortiGate')
    print('==================================================================\n')

welcome()

def aspetta():
    print('Attendiamo qualche secondo prima di passare al prossimo FortiGate...\n')
    time.sleep(5)


# ...
# Leggi l'elenco dei dispositivi dal DB
conn = sqlite3.connect('/var/www/cgi-bin/devices.db')
c = conn.cursor()

c.execute("SELECT * FROM devices")
for row in c.fetchall():
        nome_dispositivo = row[1]
        indirizzo_ip = row[2]
        porta_https = row[3]
        token = row[4]

        # Crea la directory di backup per il dispositivo corrente
        directory_backup = f"/var/www/backup/{nome_dispositivo}"
        os.makedirs(directory_backup, exist_ok=True)

        # Esegui il backup della configurazione del dispositivo corrente
        url = f"https://{indirizzo_ip}:{porta_https}/api/v2/monitor/system/config/backup?scope=global"

        print(f"Tento di collegarmi su {nome_dispositivo}")
        logger.info(f"Tento di collegarmi su {nome_dispositivo}")
        
        headers = {'Authorization': f'Bearer {token}'}
        try:
            response = requests.post(url, headers=headers, verify=False)
        except requests.exceptions.RequestException as e:
            error_message = f"Errore durante il tentativo di connessione al dispositivo {nome_dispositivo}: {e}"
            print(error_message)
            with open(log_file, 'a') as f:
                f.write(f'{error_message}\n')
            aspetta()
            continue

        if response.status_code == 200:
            # Salva il file di backup nella directory corrispondente
            now = datetime.now()
            timestamp = now.strftime("%Y-%m-%d_%H-%M-%S")
            backup_file = f"{directory_backup}/config-backup_{timestamp}.cfg"
            with open(backup_file, 'w') as f:
                f.write(response.text)

            # Verifica se il file di backup inizia con "#config"
            with open(backup_file, 'r') as f:
                first_line = f.readline().strip()
            if not first_line.startswith("#config"):
                error_message = f"Errore: il file {backup_file} non inizia con '#config'."
                print(error_message)
                logger.error(f"ATTENZIONE: {error_message}")
                with open(log_file, 'a') as f:
                    f.write(f'{error_message}\n')
                if delete_corrupted_file:
                    os.remove(backup_file) # Elimina il file non valido - Aggiungere False alle variabili globali per non eliminare il file
                    print('File non valido eliminato!')
                    logger.error(f"ATTENZIONE: il file {backup_file} è stato eliminato perchè ritenuto non valido")
            else:
                success_message = f"Backup configurazione per il dispositivo {nome_dispositivo} eseguito correttamente."
                print(success_message)
                with open(log_file, 'a') as f:
                    f.write(f'{success_message}\n')
                    logger.info(f'Backup di {nome_dispositivo} completato alle {now.strftime("%Y-%m-%d %H:%M:%S")}')
                if first_line.startswith("#config"):
                    # Aggiorna la colonna "lastbackup" del dispositivo corrente nel database
                    now = datetime.now()
                    c.execute("UPDATE devices SET lastbackup = ? WHERE device_name = ?", (now.strftime("%Y-%m-%d %H:%M:%S"), nome_dispositivo))
                    conn.commit()

        else:
            error_message = f"Errore durante il backup della configurazione per il dispositivo {nome_dispositivo}. Codice di stato HTTP {response.status_code}."
            print(error_message)
            with open(log_file, 'a') as f:
                f.write(f'{error_message}\n')
        
        #attendi 5 sec prima di processare il prossimo dispositivo
        aspetta()


# Scrivi la conclusione del backup nel file di log
now = datetime.now()
with open(log_file, 'a') as f:
    f.write(f'Fine sessione di backup alle {now.strftime("%Y-%m-%d %H:%M:%S")}\n')
    logger.info(f'Fine sessione di backup alle {now.strftime("%Y-%m-%d %H:%M:%S")}')
