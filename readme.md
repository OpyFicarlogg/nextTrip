## Accès au ws 

L'api est disponible sur le endpoint http://127.0.0.1:8000/chooseNextTrip

Avec les paramètres town1 et town2 

Exemple: `http://127.0.0.1:8000/chooseNextTrip?town1=Barcelone&town2=Lisbon`


## Fonctionnement 

L'api fait deux appels vers les api `openweathermap`

1)  https://api.openweathermap.org/geo/1.0/direct?limit=1&q=Barcelone&appid={{apiKey}} pour récupérer les coordonnées 

2)  https://api.openweathermap.org/data/2.5/onecall?exclude=hourly,current,minutely,alerts&units=metric&lat={{lat}}&lon={{lon}}&appid={{apiKey}} Pour récupérer les valeurs des 7/8 prochains jours. 

Il n'est pas possible de récupérer la semaine prochaine (lundi - dimanche) avec un compte free, il faudrait utiliser l'api `Daily Forecast 16 days` 

## Retour du ws

L'api retourne un json sous le format ci-dessous:

 ```
{
    "name": "Barcelone",
    "clouds": "68",
    "humidity": "63",
    "temp": "21",
    "startDt": 1635246000,
    "endDt": 1635850800,
    "score": 35
}
```





## A améliorer 

- Gestion des exceptions (Comment remonter correctement les exceptions entre uses ? )
- Différentes évolutions à prendre en compte 
- Ajouter plus de commentaires 
- Test unitaires 