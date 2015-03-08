#!/bin/bash
curl -i -H "Accept: application/json" http://localhost/customer?key=12345
curl -i -H "Accept: application/json" http://localhost/customer/1?key=12345
curl -i -H "Accept: application/json" -X POST -d "nameFirst=Br&nameLast=Sa&email=bs@bs.com" http://localhost/customer?key=12345
curl -i -H "Accept: application/json" -X PUT -d "nameFirst=Br&nameLast=Sa&email=bs@bs.com" http://localhost/customer/1?key=12345
curl -i -H "Accept: application/json" -X DELETE http://localhost/customer/1?key=12345