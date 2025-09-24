import sys
import os
import pandas as pd
import matplotlib.pyplot as plt
import matplotlib.dates as mdates
import json

# -------------------------------
# Leer ruta del archivo JSON
# -------------------------------
if len(sys.argv) < 2:
    print("Se requiere la ruta al archivo JSON")
    sys.exit(1)

json_file = sys.argv[1]

try:
    with open(json_file, 'r', encoding='utf-8') as f:
        data = json.load(f)
except Exception as e:
    print(f"Error leyendo JSON: {e}")
    sys.exit(1)

# Aceptar tanto lista directa como objeto con "actualizaciones"
if isinstance(data, list):
    actualizaciones = data
elif isinstance(data, dict) and "actualizaciones" in data:
    actualizaciones = data["actualizaciones"]
else:
    actualizaciones = []

# -------------------------------
# Rutas
# -------------------------------
data_path = r'D:\inflacion-main\storage\app'
output_path = os.path.join(data_path, 'public', 'grafico_update.png')

# -------------------------------
# Función para cargar CSV según país
# -------------------------------
def cargar_csv(pais):
    df = None
    if pais == 'peru':
        df = pd.read_csv(os.path.join(data_path, 'peru.csv'), encoding='latin-1')
        df = df.iloc[1:].copy()
        df = df.rename(columns={'Unnamed: 0': 'month', 'PN01272PM': 'inflacion_acumulada'})
        df['inflacion_acumulada'] = pd.to_numeric(df['inflacion_acumulada'], errors='coerce')
        df['date'] = pd.to_datetime(df['month'], format='%b%y', errors='coerce')
        df = df.dropna(subset=['date'])
        df['inflacion_mensual'] = df['inflacion_acumulada'].diff()
        df.loc[df.index[0], 'inflacion_mensual'] = df.loc[df.index[0], 'inflacion_acumulada']
    elif pais == 'mexico':
        df = pd.read_csv(os.path.join(data_path, 'mexico.csv'), encoding='latin-1')
        df = df.rename(columns={'observation_date': 'date', 'MEXCPIALLMINMEI': 'price_index'})
        df['date'] = pd.to_datetime(df['date'])
        df = df.sort_values('date')
        df['inflacion_mensual'] = df['price_index'].pct_change() * 100
        df['inflacion_acumulada'] = df['inflacion_mensual'].cumsum()
    elif pais == 'chile':
        df = pd.read_csv(os.path.join(data_path, 'chile.csv'), encoding='latin-1')
        df = df.rename(columns={'observation_date': 'date', 'CHLCPIALLMINMEI': 'price_index'})
        df['date'] = pd.to_datetime(df['date'])
        df = df.sort_values('date')
        df['inflacion_mensual'] = df['price_index'].pct_change() * 100
        df['inflacion_acumulada'] = df['inflacion_mensual'].cumsum()
    return df

# -------------------------------
# Aplicar actualizaciones
# -------------------------------
dfs = {}
for update in actualizaciones:
    pais = update.get("pais", "").lower()
    df = cargar_csv(pais)
    if df is None:
        continue

    for mes_data in update.get("meses", []):
        try:
            mes = int(mes_data.get("mes"))
            valor = float(mes_data.get("inflacion"))
        except (TypeError, ValueError):
            continue

        idx = df[df['date'].dt.month == mes].index
        if not idx.empty:
            df.loc[idx, 'inflacion_mensual'] = valor

    if 'inflacion_mensual' in df.columns:
        df['inflacion_acumulada'] = df['inflacion_mensual'].cumsum()

    dfs[pais] = df

# -------------------------------
# Generar gráfico
# -------------------------------
plt.figure(figsize=(12,6))
colors = {'peru': 'blue', 'mexico': 'green', 'chile': 'red'}

for pais, df in dfs.items():
    if df is not None and not df.empty:
        plt.plot(df['date'], df['inflacion_mensual'], label=pais.capitalize(), color=colors.get(pais, 'black'), marker='o')
        plt.fill_between(df['date'], 0, df['inflacion_mensual'], color=colors.get(pais, 'black'), alpha=0.1)

plt.gca().xaxis.set_major_formatter(mdates.DateFormatter('%b%y'))
plt.gca().xaxis.set_major_locator(mdates.MonthLocator())
plt.xlabel('Mes')
plt.ylabel('Inflación mensual (%)')
plt.title('Inflación mensual actualizada')
plt.legend()
plt.grid(True)
plt.xticks(rotation=45)
plt.tight_layout()
plt.savefig(output_path)
print(f"Gráfico generado correctamente en: {output_path}")
