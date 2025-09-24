import os
import pandas as pd
import matplotlib.pyplot as plt

# -------------------------------
# CONFIGURACIÓN DE RUTAS
# -------------------------------
# Carpeta donde están los CSV
data_path = r'D:\inflacion-main\storage\app'

# Archivos CSV
csv_peru = os.path.join(data_path, 'peru.csv')
csv_mexico = os.path.join(data_path, 'mexico.csv')
csv_chile = os.path.join(data_path, 'chile.csv')

# Ruta donde se guardará el gráfico
output_path = os.path.join(data_path, 'public', 'grafico.png')

# -------------------------------
# PERÚ
# -------------------------------
df_peru = pd.read_csv(csv_peru, encoding='latin-1')
df_peru = df_peru.iloc[1:].copy()
df_peru = df_peru.rename(columns={'Unnamed: 0': 'month', 'PN01272PM': 'inflacion_acumulada'})
df_peru['inflacion_acumulada'] = pd.to_numeric(df_peru['inflacion_acumulada'], errors='coerce')
df_peru['date'] = pd.to_datetime(df_peru['month'], format='%b%y', errors='coerce')
df_peru = df_peru.dropna(subset=['date'])
df_peru['inflacion_mensual'] = df_peru['inflacion_acumulada'].diff()
df_peru.loc[df_peru.index[0], 'inflacion_mensual'] = df_peru.loc[df_peru.index[0], 'inflacion_acumulada']

# -------------------------------
# MÉXICO
# -------------------------------
df_mexico = pd.read_csv(csv_mexico, encoding='latin-1')
df_mexico = df_mexico.rename(columns={'observation_date': 'date', 'MEXCPIALLMINMEI': 'price_index'})
df_mexico['date'] = pd.to_datetime(df_mexico['date'])
df_mexico = df_mexico.sort_values('date')
df_mexico['inflacion_mensual'] = df_mexico['price_index'].pct_change() * 100
df_mexico['inflacion_acumulada'] = df_mexico['inflacion_mensual'].cumsum()

# -------------------------------
# CHILE
# -------------------------------
df_chile = pd.read_csv(csv_chile, encoding='latin-1')
# Ajusta el nombre de las columnas si es necesario
df_chile = df_chile.rename(columns={'observation_date': 'date', 'CHLCPIALLMINMEI': 'price_index'})
df_chile['date'] = pd.to_datetime(df_chile['date'])
df_chile = df_chile.sort_values('date')
df_chile['inflacion_mensual'] = df_chile['price_index'].pct_change() * 100
df_chile['inflacion_acumulada'] = df_chile['inflacion_mensual'].cumsum()

# -------------------------------
# GRÁFICO
# -------------------------------
plt.figure(figsize=(12,6))

plt.plot(df_peru['date'], df_peru['inflacion_mensual'], label='Perú', color='blue', marker='o')
plt.fill_between(df_peru['date'], 0, df_peru['inflacion_mensual'], color='blue', alpha=0.1)

plt.plot(df_mexico['date'], df_mexico['inflacion_mensual'], label='México', color='green', marker='o')
plt.fill_between(df_mexico['date'], 0, df_mexico['inflacion_mensual'], color='green', alpha=0.1)

plt.plot(df_chile['date'], df_chile['inflacion_mensual'], label='Chile', color='red', marker='o')
plt.fill_between(df_chile['date'], 0, df_chile['inflacion_mensual'], color='red', alpha=0.1)

plt.xlabel('Mes')
plt.ylabel('Inflación mensual (%)')
plt.title('Inflación mensual 2023 y su variación')
plt.legend()
plt.grid(True)
plt.xticks(rotation=45)
plt.tight_layout()

# Guardar gráfico
plt.savefig(output_path)
print(f"Gráfico guardado en: {output_path}")
