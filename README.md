# Yanis

## Liste des commandes effectuées :

### Microservice Java (RentalService)

Après l'installation de Java, se placer dans le dossier du projet et compiler :
```bash
cd RentalService
./gradlew build
```
Lancer l'application :
```java
java -jar build/libs/RentalService-0.0.1-SNAPSHOT.jar  
```
Accéder à l'URL pour tester :
[http://localhost:8080/bonjour](http://localhost:8080/bonjour)

Ensuite, toujours dans `RentalService`, créer le `Dockerfile` :
```bash
touch Dockerfile
```
Contenu du `Dockerfile` :
```dockerfile
FROM eclipse-temurin:21-jre-jammy
VOLUME /tmp
EXPOSE 8080
ADD ./build/libs/RentalService-0.0.1-SNAPSHOT.jar app.jar
ENTRYPOINT ["java","-Djava.security.egd=file:/dev/./urandom","-jar","/app.jar"]
```
Construire l'image Docker :
```bash
docker build –t rental-service .
```
Lancer le conteneur :
```bash
docker run -p 8080:8080 rental-service
```
Accéder à l'URL pour tester :
[http://localhost:8080/bonjour](http://localhost:8080/bonjour)

### Microservice PHP (PHPService)

Créer le dossier `PHPService` et s'y placer :
```bash
mkdir PHPService
cd PHPService
```

Créer le fichier `index.php` :
```bash
touch index.php
```
Contenu du `index.php` :
```php
<?php
header('Content-Type: application/json');

$response = [
    "surname" => "Yanis",
];

echo json_encode($response);
?>
```

Créer le `Dockerfile` :
```bash
touch Dockerfile
```
Contenu du `Dockerfile` :
```dockerfile
FROM php:8.2-apache
COPY index.php /var/www/html/
EXPOSE 8081
```

Construire l'image Docker :
```bash
docker build -t phpservice .
```

Tester le service :
```bash
docker run -p 8080:80 phpservice
```
Accéder à l'URL pour tester :
[http://localhost:8080/index.php](http://localhost:8080/index.php)

Publication sur Docker Hub :
```bash
# Se connecter à Docker Hub
docker login

# Tagger l'image avec le nom d'utilisateur Docker Hub
docker tag phpservice:latest seyyzi/phpservice:latest

# Pousser l'image sur Docker Hub
docker push seyyzi/phpservice:latest
```

### Communication entre microservices avec Docker Compose

Modification du fichier `BonjourController.java` pour ajouter la route `/bonjour-php` qui appellera le service PHP.

Recompiler le projet Java :
```bash
cd RentalService
./gradlew clean build
```

Créer le fichier `docker-compose.yml` à la racine du projet :
```bash
cd ..
touch docker-compose.yml
```
Contenu du `docker-compose.yml` :
```yaml
version: '3.8'

services:
  rental-service:
    build: ./RentalService
    container_name: rental-service
    ports:
      - "8080:8080"
    networks:
      - app-network
    depends_on:
      - php-service

  php-service:
    build: ./PHPService
    container_name: php-service
    ports:
      - "8081:80"
    networks:
      - app-network

networks:
  app-network:
    driver: bridge
```

Lancer les services avec docker-compose :
```bash
docker-compose up --build
```

### Points de test :
- Service Java : [http://localhost:8080/bonjour](http://localhost:8080/bonjour)
- Service PHP : [http://localhost:8081/index.php](http://localhost:8081/index.php)
- Communication Java -> PHP : [http://localhost:8080/bonjour-php](http://localhost:8080/bonjour-php)

### Mise à jour de l'image Java sur Docker Hub :
```bash
# Construire l'image en la taggant directement avec votre nom d'utilisateur
docker build -t seyyzi/rental-service:latest ./RentalService

# Pousser l'image sur Docker Hub
docker push seyyzi/rental-service:latest
```