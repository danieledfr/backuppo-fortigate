import sqlite3

def create_database_and_table():
    conn = sqlite3.connect('devices.db')
    c = conn.cursor()

    c.execute('''CREATE TABLE IF NOT EXISTS devices
                (device_name TEXT, ip_address TEXT, https_port INTEGER, token TEXT)''')

    conn.commit()
    conn.close()

create_database_and_table()
