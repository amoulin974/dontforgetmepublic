# DON'T FORGET ME
[Accès à la doc](https://amoulin974.github.io/dontforgetmepublic/)
## À propos de DON'T FORGET ME

Dans le cadre de leurs activités, de nombreux professionnels recevant des clients ont exprimés leur mécontentement face à des rendez-vous non honorés. <br>
Ces absences représentent un manque à gagner considérable et nuisent à leur organisation. <br>
Pour répondre à ce problème, le projet Dont Forget Me vise à développer une solution complète permettant à ces professionnels : <br>
•    Gérer leurs rendez-vous selon leurs besoins spécifiques, <br>
•    Envoyer automatiquement des rappels à leurs clients par mail ou par SMS. <br>

Le projet se structure en deux volets principaux : <br>
### 1. Application web : une plateforme en ligne responsive permettant : <br>
• l’inscription des professionnels, <br>
• la gestion des rendez-vous et des plannings, <br>
• la consultation et la gestion des informations clients. <br>

### 2.Configuration d’un Raspberry Pi : <br>
Ce dispositif communiquera avec l’application web pour récupérer les données des clients pour leur envoyer des notifications par mail ou par SMS afin de les inciter à honorer leur rendez-vous. <br>

Ce projet vise donc à offrir une solution d’intermédiation simple et efficace entre des professionnels proposant des services et des clients souhaitant y accéder en ayant pour objectif d’améliorer l’organisation des premiers et accompagner les seconds dans leur expérience de prise de rendez-vous pour une prestation.

## Installation VPS

### 1. Connexion au VPS(ubuntu 24.04 LTS (Noble Numbat) + SSH) via SSH 

Connectez-vous à votre VPS en utilisant SSH : 

```
ssh root@votre-ip
``` 
 
Mettre les paquets à jour : 

```
sudo apt update && sudo apt upgrade -y
```
 

### 2. Installation de Nginx 

Installez Nginx : 

```
sudo apt install nginx -y
```

Vérifiez que Nginx fonctionne : 

```
systemctl status nginx
``` 

Accédez à votre serveur via l’adresse IP pour vérifier l’installation nginx par défaut. 

### 3. Lier un nom de domaine au serveur 

Configurez votre nom de domaine en créant un enregistrement A (dans votre DNS) qui pointe vers l’adresse IP de votre VPS. 

Testez la résolution DNS (peut prendre entre 2h a 24h): 

https://dnschecker.org/ 

```
ping votre-domaine.com
``` 
 
### 4. Mise en place des certificats SSL (Let's Encrypt) 

Installez Certbot et son plugin pour Nginx : 

```
sudo apt install certbot python3-certbot-nginx -y
```
 
Générez et appliquez un certificat SSL : 

```
sudo certbot --nginx -d votre-domaine.com -d www.votre-domaine.com
``` 
 
Testez le renouvellement automatique : 

```
sudo certbot renew --dry-run
``` 
 
### 5. Suppression du site par défaut 

Désactivez la configuration par défaut : 
```
sudo rm /etc/nginx/sites-enabled/default 
sudo rm /etc/nginx/sites-available/default 
```
Redémarrez Nginx pour appliquer les changements :  

```
sudo systemctl restart nginx
``` 
 
### 6. Cloner le projet Laravel 

Accédez au dossier où vous souhaitez cloner le projet : 

```
cd /var/www/
``` 
 
Installer et Clonez le dépôt Git : 

```
sudo apt install git -y  
git clone https://github.com/amoulin974/dontforgetmepublic
```
Naviguez dans le dossier du projet backend : 

```
cd DontForgetMe/S5DevBack/DevLaravel/
``` 
 
### 7. Installer les dépendances Laravel 

Installez Composer si ce n’est pas encore fait : 

```
sudo apt install composer -y
``` 
 
Installez les dépendances du projet : 

```
composer install
``` 
 
Installez les extensions manquantes avec la commande suivante : 

```
sudo apt update 

sudo apt install php8.3-fpm 

sudo systemctl enable php8.3-fpm 

sudo apt install php8.3-xml 

sudo apt install php8.3-mysql 
 ```
Générez une clé d’application Laravel : 

```
php artisan key:generate
``` 
 
### 8. Configurer la base de données 

```
sudo apt install mysql-server -y
``` 

#### 1. Connectez-vous à MySQL avec un utilisateur ayant les privilèges appropriés (par exemple, root) : 

```
mysql -u root -p
``` 
 

##### 2. Créez la base de données. Utilisez le même nom que celui spécifié dans votre fichier .env (dans cet exemple : bd_dfm) : 

```
CREATE DATABASE bd_dfm;
``` 

#### 3. Créez un utilisateur MySQL dédié et attribuez-lui des droits : 
```
CREATE USER 'laravel_user'@'localhost' IDENTIFIED BY 'mot_de_passe'; 
GRANT ALL PRIVILEGES ON bd_dfm.* TO 'laravel_user'@'localhost'; 
FLUSH PRIVILEGES; 
 ```

Remplacez laravel_user et mot_de_passe par vos propres valeurs. 

#### 4. Quittez MySQL : 

```
EXIT;
``` 


Modifiez le fichier .env pour configurer les informations de connexion à la base de données . 

Exemple de configuration dans le fichier .env : 

```
APP_NAME=Laravel APP_ENV=local APP_KEY=base64:vSaIcedhMRoXcZ4bX0pG6Oa/eb3Q/6D6ErC6uiVBV7s= APP_DEBUG=true APP_URL=https://dontforgetme.online 

LOG_CHANNEL=stack LOG_DEPRECATIONS_CHANNEL=null LOG_LEVEL=debug 

DB_CONNECTION=mysql DB_HOST=127.0.0.1 DB_PORT=3306 DB_DATABASE=bd_dfm DB_USERNAME=laravel_user DB_PASSWORD=mot_de_passe 

CACHE_DRIVER=file SESSION_DRIVER=file SESSION_LIFETIME=120 

REDIS_HOST=127.0.0.1 REDIS_PASSWORD=null REDIS_PORT=6379 

MAIL_MAILER=smtp MAIL_HOST=mailhog MAIL_PORT=1025 MAIL_USERNAME=null MAIL_PASSWORD=null MAIL_ENCRYPTION=null MAIL_FROM_ADDRESS=null MAIL_FROM_NAME="${APP_NAME}" 

AWS_ACCESS_KEY_ID= AWS_SECRET_ACCESS_KEY= AWS_DEFAULT_REGION=us-east-1 AWS_BUCKET= AWS_USE_PATH_STYLE_ENDPOINT=false 

PUSHER_APP_ID= PUSHER_APP_KEY= PUSHER_APP_SECRET= PUSHER_APP_CLUSTER=mt1 

VITE_APP_NAME="${APP_NAME}" 
```
 

Appliquez les migrations pour créer les tables nécessaires : 

```
php artisan migrate
``` 
 

### 9. Configurer Nginx pour le projet Laravel 

Créez un nouveau fichier de configuration Nginx : 

```
sudo nano /etc/nginx/sites-available/dontforgetme
```
 
Ajoutez la configuration suivante : 
```
server { listen 443 ssl; server_name dontforgetme.online www.dontforgetme.online; 

ssl_certificate /etc/letsencrypt/live/dontforgetme.online/fullchain.pem; 
ssl_certificate_key /etc/letsencrypt/live/dontforgetme.online/privkey.pem; 
 
root /var/www/DontForgetMe/S5DevBack/DevLaravel/public; 
index index.php index.html; 
 
location / { 
    try_files $uri $uri/ /index.php?$query_string; 
} 
 
location ~ \.php$ { 
    include snippets/fastcgi-php.conf; 
    fastcgi_pass unix:/var/run/php/php8.3-fpm.sock; 
    fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name; 
    include fastcgi_params; 
} 
 
location ~ /\.ht { 
    deny all; 
} 
  

} 
```
Activez la configuration et redémarrez Nginx : 
```
sudo ln -s /etc/nginx/sites-available/dontforgetme /etc/nginx/sites-enabled/ 
sudo systemctl restart nginx 
 ```
### 10. Configurer les permissions 

Laravel nécessite que certains dossiers aient les bonnes permissions pour fonctionner correctement (notamment les dossiers storage et bootstrap/cache) : 
```
sudo chown -R www-data:www-data /var/www/DontForgetMe/S5DevBack/DevLaravel 
sudo chmod -R 775 /var/www/DontForgetMe/S5DevBack/DevLaravel/storage 
sudo chmod -R 775 /var/www/DontForgetMe/S5DevBack/DevLaravel/bootstrap/cache 
 ```
### 11. Activer SSL (Let's Encrypt) 

Installez Certbot si ce n’est pas fait : 

```
sudo apt install certbot python3-certbot-nginx -y
```
 
Activez SSL pour le domaine : 

```
sudo certbot --nginx -d votre-domaine.com -d www.votre-domaine.com
```
 
Vérifiez que le certificat est actif : 

```
sudo certbot renew --dry-run
```

## Installation Raspberry Pi

### 1. Créer un fichier script.py

Ouvrir le terminal
Sur votre Raspberry Pi, ouvrez le terminal (l'invite de commande).

Accéder au répertoire souhaité
exemple :

```
cd Desktop/DontForgetMe
```

créer le fichier pour le script:

```
nano script.py
```

Pour obtenir le token de l'api utilisez postman et un compte super admin. envoyer un POST vers nomdedomain.com/login

body :
```
{
    "email":"example@gmail.com",
    "password": "example"
}
```
Voici le script à ajouter à votre fichier. Veuillez modifier l'adresse e-mail ainsi que le mot de passe d'application et L'url de l'API avec son token.

 ```
import requests
import smtplib
from email.mime.text import MIMEText
from email.mime.multipart import MIMEMultipart
from datetime import datetime, timedelta
import certifi
import serial
import time

# =====================
# Configurations
# =====================

# URL de l'API pour récupérer les détails des rendez-vous
API_URL = "https://nomdedomaine.com/api/details"
# Jeton d'authentification pour l'API
TOKEN = "1|tokendauthentification"

# Configuration SMTP pour l'envoi d'emails
SMTP_SERVER = "smtp.gmail.com"
SMTP_PORT = 587
SMTP_EMAIL = "example@gmail.com"
SMTP_PASSWORD = "xxxx xxxx xxxx xxxx" # mot de passe d'application (via compte google)

# Configuration du modem pour l'envoi de SMS
MODEM_PORT = '/dev/ttyS0' #port du modem
BAUDRATE = 115200

# =====================
# Fonctions utilitaires
# =====================

# Fonction pour envoyer un e-mail
# @param destinataire: Adresse e-mail du destinataire
# @param sujet: Sujet de l'e-mail
# @param corps: Contenu de l'e-mail
def envoyer_email(destinataire, sujet, corps):
    try:
        # Création de l'e-mail
        msg = MIMEMultipart()
        msg["From"] = SMTP_EMAIL
        msg["To"] = destinataire
        msg["Subject"] = sujet
        msg.attach(MIMEText(corps, "plain"))

        # Connexion au serveur SMTP et envoi de l'e-mail
        with smtplib.SMTP(SMTP_SERVER, SMTP_PORT) as server:
            server.starttls()
            server.login(SMTP_EMAIL, SMTP_PASSWORD)
            server.send_message(msg)

        print(f"[OK] E-mail envoyé à {destinataire}.")
    except Exception as e:
        print(f"[ERREUR] Échec de l'envoi de l'e-mail à {destinataire} : {e}")

# Fonction pour envoyer un SMS
# @param numero: Numéro de téléphone du destinataire
# @param message: Contenu du SMS
def envoyer_sms(numero, message):
    try:
        # Connexion au modem via le port série
        modem = serial.Serial(MODEM_PORT, BAUDRATE, timeout=1)

        # Fonction interne pour envoyer une commande AT
        def send_at_command(command, delay=1):
            modem.write((command + '\r').encode())
            time.sleep(delay)
            response = modem.read(modem.inWaiting()).decode()
            if "+CMS ERROR" in response:
                raise Exception(f"Erreur du modem : {response}")
            return response

        # Initialisation et envoi du message
        send_at_command('AT')
        send_at_command('AT+CMGF=1')  # Mode texte pour les SMS
        send_at_command(f'AT+CMGS="{numero}"')
        modem.write((message + chr(26)).encode())  # Fin du message avec Ctrl+Z
        time.sleep(10)

        print(f"[OK] SMS envoyé à {numero}.")
        modem.close()
    except Exception as e:
        print(f"[ERREUR] Échec de l'envoi du SMS à {numero} : {e}")

# Fonction pour convertir des heures en timedelta
# @param hours: Nombre d'heures à convertir
def convertir_time_en_timedelta(hours):
    return timedelta(hours=hours)

# =====================
# Logique principale
# =====================

# Fonction principale pour envoyer des rappels
def envoyer_rappels():
    try:
        # Configuration des en-têtes pour l'API
        headers = {
            "Authorization": f"Bearer {TOKEN}",
            "Content-Type": "application/json"
        }

        # Récupération des données de l'API
        response = requests.get(API_URL, headers=headers, verify=certifi.where())
        if response.status_code != 200:
            print(f"[ERREUR] Impossible de récupérer les données. Code HTTP: {response.status_code}")
            return

        data = response.json()
        print("Traitement des notifications :")

        # Traitement de chaque rendez-vous
        for rdv in data:
            if rdv["notifEtat"] == 0:  # Vérifie si la notification n'a pas encore été envoyée
                # Calcul du délai avant notification
                delaiAvantNotif = convertir_time_en_timedelta(rdv["notifDelaiAvantNotif"])
                rdv_datetime = datetime.strptime(f"{rdv['dateRendezVous']} {rdv['heureRendezVous']}", "%Y-%m-%d %H:%M:%S")
                maintenant = datetime.now()
                seuil_notification = rdv_datetime - delaiAvantNotif

                # Vérifie si le moment d'envoyer la notification est arrivé
                if seuil_notification <= maintenant <= rdv_datetime:
                    if rdv["notifCategorie"].lower() == "mail":
                        envoyer_email(
                            rdv["userEmail"],
                            "Rappel de rendez-vous",
                            f"Bonjour {rdv['userPrenom']} {rdv['userNom']},\n\n"
                            f"Rappel pour votre rendez-vous avec {rdv['entrepriseNom']}.\n"
                            f"Date et heure : {rdv['dateRendezVous']} à {rdv['heureRendezVous']}.\n"
                            f"Modifier rendez-vous : https://dontforgetme.online/reserver/{rdv['notifId']}.\n"
                            "Merci."
                        )
                    elif rdv["notifCategorie"].lower() == "sms":
                        envoyer_sms(
                            rdv["userNumTel"],
                            f"Rappel RDV: {rdv['entrepriseNom']}, {rdv['dateRendezVous']} {rdv['heureRendezVous']}."
                        )

                    # Mise à jour de l'état de la notification via une requête PATCH
                    patch_url = f"{API_URL}/{rdv['notifId']}"
                    payload = {"etat": 1}
                    patch_response = requests.patch(patch_url, headers=headers, json=payload, verify=certifi.where())
                    if patch_response.status_code == 200:
                        print(f"[OK] Notification {rdv['notifId']} mise à jour.")
                    else:
                        print(f"[ERREUR] Échec mise à jour notification {rdv['notifId']}.")
    except Exception as e:
        print(f"[ERREUR] Problème lors du traitement des rappels : {e}")

def supprimer_notification(notif_id):
    try:
        headers = {
            "Authorization": f"Bearer {TOKEN}",
            "Content-Type": "application/json"
        }
        delete_url = f"{API_URL}/{notif_id}"
        response = requests.delete(delete_url, headers=headers, verify=certifi.where())
        if response.status_code == 200:
            print(f"[OK] Notification {notif_id} supprimée.")
        else:
            print(f"[ERREUR] Impossible de supprimer la notification {notif_id}. Code HTTP: {response.status_code}")
    except Exception as e:
        print(f"[ERREUR] Échec de suppression de la notification {notif_id} : {e}")


def verifier_et_supprimer_anciennes_notifications():
    try:
        headers = {
            "Authorization": f"Bearer {TOKEN}",
            "Content-Type": "application/json"
        }
        response = requests.get(API_URL, headers=headers, verify=certifi.where())
        if response.status_code != 200:
            print(f"[ERREUR] Impossible de récupérer les notifications. Code HTTP: {response.status_code}")
            return

        data = response.json()
        maintenant = datetime.now()
        seuil_passe = maintenant - timedelta(days=10)

        for rdv in data:
            rdv_datetime = datetime.strptime(f"{rdv['dateRendezVous']} {rdv['heureRendezVous']}", "%Y-%m-%d %H:%M:%S")
            if rdv["notifEtat"] == 1 and rdv_datetime < seuil_passe:
                supprimer_notification(rdv["notifId"])
    except Exception as e:
        print(f"[ERREUR] Problème lors de la vérification des anciennes notifications : {e}")


if __name__ == "__main__":
    envoyer_rappels()
    verifier_et_supprimer_anciennes_notifications()

```

### 2. Mise en place du cron

ouvrer la crontab via la commande suivante:

```
sudo crontab -e
```
Ajouter la tâche cron (ici exécution toutes les 4 heures):

```
0 */4 * * * /usr/bin/python3 /Desktop/dontforgetme/script.py
```
## Merci d’avoir installé DON'T FORGET ME !

Nous vous remercions pour votre confiance et votre intérêt pour notre solution. Grâce à DON'T FORGET ME, vous pourrez désormais gérer vos rendez-vous en toute simplicité, tout en réduisant les absences !

Notre objectif est de vous accompagner dans l’optimisation de votre organisation et dans la satisfaction de vos clients.

Si vous avez des questions, des suggestions ou besoin d’aide, notre équipe reste à votre disposition. N’hésitez pas à nous contacter !

Bonne gestion et à bientôt !

– L’équipe DON'T FORGET ME
<br>
<br>
ROUYER Johan <br>
AUDOUARD Raphaël  <br>
GUIHENEUF Mattin - mattin.guiheneuf@gmail.com <br>
MOURGUE Clément  <br>
VICTORAS Dylan <br>
