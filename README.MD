# OctopusPHP

Aplicación PHP ligera preparada para ejecutarse con Docker y Docker Compose. Incluye MySQL (con inicialización desde `database.sql`) y phpMyAdmin.


## Requisitos para instalación

- Git
- Docker (Engine) y Docker Compose

## Instalación y arranque (con docker)

1. Clona el repositorio:

```bash
git clone https://github.com/khal1997jorge/octopusphp.git
cd octopusphp
```

2. Copia los ejemplos de configuración al nombre de archivo que usa la app:

```bash
cp .env.example .env
cp config/app.ini.example config/app.ini
```

3. (Opcional) Edita `.env` y `config/app.ini` si necesitas cambiar puertos, credenciales o base de datos:

```bash
# ejemplo
nano .env
nano config/app.ini
```

4. Construye y arranca los contenedores:

```bash
docker compose up --build
```

5. Verifica que los servicios estén activos:

```bash
docker compose ps
```

6. Accede a la aplicación y a phpMyAdmin desde tu navegador:

- Aplicación: http://localhost:-3001 
- phpMyAdmin: http://localhost:8080

> Si modificaste los valores por defecto del .env ajusta el puerto.

7. Parar y eliminar contenedores (cuando lo necesites):

```bash
docker compose down -v
```