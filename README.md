## Commandes utilisées

### Build du projet
./gradlew build

### Lancement sans Docker
java -jar build/libs/RentalService-0.0.1-SNAPSHOT.jar

### Build de l’image Docker
docker build -t rentalservice .

### Lancement du conteneur
docker run -p 8080:8080 rentalservice