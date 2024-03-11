# Commande utiles

### Compilation container
```bash
docker-compose up --build
```

### Lancer les containers
```bash
docker-compose up
```

### Compilation container
```bash
docker-compose up --build
```

## NODE

### Entrer dans le container Node
```bash
docker exec -it node sh
```

### Error node exited with code 127

commenter la ligne suivante dans /Docker/Node/Dockerfile
```bash
CMD [ "npm", "run", "dev" ]
```
Entrer dans le container

```bash
docker exec -it node sh
```

Installer package vue/cli

```bash
npm install @vue/cli
```

Lancer serveur

```bash
npm run dev
```

### Lancer front
```bash
npm run dev
```

## Symfony

### Entrer dans le container

```bash
docker exec -it php-fpm bash
```

### Commande Symfony

```bash
symfony console [OPTIONS]
```

