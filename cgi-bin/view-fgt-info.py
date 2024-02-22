import sqlite3
import httpx
import requests

# Funzione per estrarre la lista di firewall dal database
def get_firewall_list(db_path):
    """
    Questa funzione estrae la lista di firewall dal database SQLite.

    Args:
        db_path (str): Il percorso del database SQLite.

    Returns:
        list: Una lista di dizionari, dove ogni dizionario rappresenta un firewall.
    """
    with sqlite3.connect(db_path) as conn:
        cursor = conn.cursor()
        cursor.execute("SELECT device_name, ip_address, https_port, token FROM devices")
        # Converti ogni tupla in un dizionario
        return [dict(zip(('device_name', 'ip_address', 'https_port', 'token'), row)) for row in cursor.fetchall()]

# Funzione per estrarre la versione di FortiOS da un firewall FortiGate
def get_fortios_version(firewall):
    """
    Questa funzione estrae la versione di FortiOS da un firewall FortiGate.

    Args:
        firewall (dict): Un dizionario che rappresenta un firewall.

    Returns:
        str: La versione di FortiOS del firewall.
    """
    url = f"https://{firewall['ip_address']}:{firewall['https_port']}/api/v2/monitor/system/status"
    headers = {
        "Authorization": f"Bearer {firewall['token']}",
    }

    try:
        response = httpx.get(url, headers=headers, verify=False, timeout=5)
        if response.status_code == 200:
            return response.json()['version']
        else:
            raise RuntimeError(f"Errore durante la chiamata API al firewall {firewall['device_name']}: {response.status_code}")
    except httpx.RequestError as exc:
        print(f"Errore di richiesta per il firewall {firewall['device_name']}: {exc}")
    except requests.exceptions.SSLError:
        print(f"Errore SSL per il firewall {firewall['device_name']}: Impossibile verificare il certificato")

# Lista di firewall
firewall_list = get_firewall_list("/var/www/cgi-bin/devices.db")

# Lista per le informazioni estratte
info_list = []

# Estrazione delle informazioni per ogni firewall
for firewall in firewall_list:
    try:
        fortios_version = get_fortios_version(firewall)
        info_list.append(f"{firewall['device_name']}: {fortios_version}")
    except RuntimeError as e:
        print(f"Errore per il firewall {firewall['device_name']}: {e}")
        continue

# Crea il file .txt solo se tutti i firewall sono stati elaborati
if len(info_list) == len(firewall_list):
    with open("info_firewall.txt", "w") as f:
        f.write("\n".join(info_list))

print("Informazioni estratte correttamente!")
