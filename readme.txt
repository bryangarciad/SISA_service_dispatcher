SISA Servie emitter software by ramses garcia:

Client Actions:
    Create client
    Set default client service
    Delete client
    bulk Create client

Service Actions:
    get services All
    (Filter Actions)
    get Services By Site
    get Services by Date
    Schedule Service
    register service type

emit Actions
    send service
    send scheduled services


user Actions
    CRUD

operator actions:
    register operator with Sign
    match services with operator

settings actions 
     CRUD
     setting will handle
     users, user_rols, operators_service, registration_ids


Db tables:
user:
    id
    user_name
    password
    Site
    rol

services:
    id
    client_id
    service_type_id
    amount

service_type:
    id
    name
    UOM

client
    id
    name 
    contact_phone
    contact_emails
    
default_service
    id
    client_id
    service_id
    amount

operator
    id
    name
    sign

operator_service_type
    id
    operator_id
    service_type_id

Site
    id
    site_name

registration_ids
    id
    name
    key_name

ROL:
RG - read global 
RL -- readlocal
E -- generate services
S -- Set setting Variables (Operator service, registration_ids, default services)
CC -- createClients
CS -- create services
CU -- create users
DU -- Delete users
DC -- Delete Clients


TODO:::

Update Authentication service
Create services emition using API

TODO:::
create action for service handlers
and default service actions TODO
create model builder for pritn form


BULK ACTIONS AFTER DEMO WITH SISA <<<<<<<<---------

