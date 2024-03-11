FROM node:lts-alpine

# make the 'app' folder the current working directory
WORKDIR /app

# copy both 'package.json' and 'package-lock.json' (if available)
COPY package*.json /app

# copy project files and folders to the current working directory (i.e. 'app' folder)
COPY . .

USER root

EXPOSE 9100

# CMD [ "npm", "run", "dev" ]
