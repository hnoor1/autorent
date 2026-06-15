**Autorent**

Tegemist on veebilehega, mille abil saab autosid rentida. 

**Veebilehe funktsioonid**

Kasutaja

- Kasutaja saab veebilehel luua konto ning seejärel sisse logida.

![screenshot](https://github.com/hnoor1/autorent/blob/too4-docker-rent/pildid/registreerimine.jpg)
![screenshot](https://github.com/hnoor1/autorent/blob/too4-docker-rent/pildid/sisselogimine.jpg)
![screenshot](https://github.com/hnoor1/autorent/blob/too4-docker-rent/pildid/login%20eba%C3%B5nnestumine.jpg)
- Kasutaja saab ülevaate renditavatest autodest.


![screenshot](https://github.com/hnoor1/autorent/blob/too4-docker-rent/pildid/autorent%20%C3%BClevaade.jpg)
- Kasutaja saab teha broneeringuid.


![screenshot](https://github.com/hnoor1/autorent/blob/too4-docker-rent/pildid/auto%20rentimine%2C%20kui%20see%20on%20vaba.jpg)
![screenshot](https://github.com/hnoor1/autorent/blob/too4-docker-rent/pildid/auto%20rentimise%20kinnitus.jpg)


- Veebileht ei lase broneerida hoolduses olevat autot.


![screenshot](https://github.com/hnoor1/autorent/blob/too4-docker-rent/pildid/hoolduses%20auto%20rentimine.jpg)
- Veebileht ei lase samaks perioodiks ühte ning sama autot broneerida.


![screenshot](https://github.com/hnoor1/autorent/blob/too4-docker-rent/pildid/topeltbronn.jpg)
- Kasutaja saab oma broneeringuid vaadata ning neid ka tühistada.

![screenshot](https://github.com/hnoor1/autorent/blob/too4-docker-rent/pildid/kasutaja%20broneeringute%20haldamine.jpg)


Admin

- Admin saab lisada uusi autosid ning muuta olemasolevaid.
![screenshot](https://github.com/hnoor1/autorent/blob/too4-docker-rent/pildid/admin%20haldus.jpg)
![screenshot](https://github.com/hnoor1/autorent/blob/too4-docker-rent/pildid/admin%20auto%20lisamine.jpg)


- Admin saab broneeringuid hallata (kustutada, tühistada, aktsepteerida).

![screenshot](https://github.com/hnoor1/autorent/blob/too4-docker-rent/pildid/admin%20bron%20haldus.jpg)
![screenshot](https://github.com/hnoor1/autorent/blob/too4-docker-rent/pildid/admin%20bron%20kustutamine.jpg)

Turvalisus

- Veebilehel ei saa autosid topelt broneerida.
- Tavakasutaja ei saa ligi admin vaatele, st tavakasutajale ei kuvata admin vahelehte.
- Autot ei saa rentida ilma sisselogimata.
![screenshot](https://github.com/hnoor1/autorent/blob/too4-docker-rent/pildid/peab%20sisselogima.jpg)


Projekti ülevaade

- Klooni projekt.
- Käivita docker kasutades käsku docker compose up -d --build
- Veebileht avaneb:
     - http://localhost:8080/
     - http://localhost:8081/
