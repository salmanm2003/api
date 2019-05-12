# api

This API is to get the phone numbers of customers or activating it.

To use the API:

1- Getting all the phone numbers use request:
GET http://localhost/api/phonenumbers/

2- Getting phone numbers for a customer use:
GET http://localhost/api/phonenumbers/customer/1

3- Activating a phone number use:
PUT http://localhost/api/phonenumbers/05432534/

Example of the returened data:

[{"number":"05432534"},{"number":"053453425"}]

If the API endpoint is not in the right format the system will send a non found page.


