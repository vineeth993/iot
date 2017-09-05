import requests
import json

url = "http://localhost/MyApi/"

payload = json.dumps({"Value":1000, "dateTime":"234567"})
print payload
headers = {'Content-Type': 'application/json'}

request = requests.post(url, data=payload, headers=headers)

print "staus = ", request.status_code
print "content = ", request.content 
