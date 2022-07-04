# TRT Conseil

Evaluation réalisé dans le cadre de mes études chez Studi.


## Variables d'environment

Lancez votre server MAMP et vérifier que le port mysql dans .env correspond bien à votre port. Ici il s'agit de 8889 : 

`DATABASE_URL="mysql://root:root@127.0.0.1:8889/trt?serverVersion=5.7&charset=utf8mb4"`



## Lancer en local

Clonez le projet

```bash
  git clone https://github.com/AlinePencreach/trt_conseil_studi.git
```

Rendez vous sur le chemin

```bash
  cd trt_conseil
```

Installez les dépendences

```bash
  composer install
```


Faites la migration
```bash
  php bin/console make:migration
```

Démarrer le serveur symfony

```bash
  symfony server:start
```

Explorez les fonctionnalités grace au manuel d'utilisation. 
