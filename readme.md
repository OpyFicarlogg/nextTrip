## Accès au ws 


Lancer le server avec la commande: `symfony server:start`

L'api est disponible sur le endpoint http://127.0.0.1:8000/chooseNextTrip

Avec les paramètres town1 et town2 

Exemple: `http://127.0.0.1:8000/chooseNextTrip?town1=Barcelone&town2=Lisbon`

*Erreur connue* : Exception mal gérée dans le cas d'une ville n'existant pas, ou mal renseignée (exemple: Barcelon)

## Fonctionnement 

L'api fait deux appels vers les api `openweathermap`

1)  https://api.openweathermap.org/geo/1.0/direct?limit=1&q=Barcelone&appid={{apiKey}} pour récupérer les coordonnées 

2)  https://api.openweathermap.org/data/2.5/onecall?exclude=hourly,current,minutely,alerts&units=metric&lat={{lat}}&lon={{lon}}&appid={{apiKey}} Pour récupérer les valeurs des 7/8 prochains jours. 

Il n'est pas possible de récupérer la semaine prochaine (lundi - dimanche) avec un compte free, il faudrait utiliser l'api `Daily Forecast 16 days` 

## Retour du ws

L'api retourne un json sous le format ci-dessous:

 ```
{
    "name": "Barcelone",    //String
    "clouds": "68",         //Integer
    "humidity": "63",       //Integer
    "temp": "21",           //Integer
    "startDt": 1635246000,  //Timestamp
    "endDt": 1635850800,    //Timestamp
    "score": 35             //Integer 
}
```


## Maquette site
![Alt text](/imgMd/frame1.png "frame 1")


![Alt text](/imgMd/frame2.png "frame2")



## A améliorer 

- Gestion des exceptions (Comment remonter correctement les exceptions entre uses ? )
- Différentes évolutions à prendre en compte 
- Ajouter plus de commentaires 
- Test unitaires 