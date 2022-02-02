<?php

// call Excel reader to get client data must return an array with valid objs
// like:
// array(0 => [
//     "name": "test_client_1222",
//     "contact_phone": [
//         "6142892234",
//         "6145148454"
//     ],
//     "contact_email": [
//         "a@gmail.com",
//         "b@gmail.com"
//     ],
//     "rfc": "sdfdafadf",
//     "direccion": "dsfsdf",
//     "ciudad": "chihuahua",
//     "estado": "chihuahua"
// ], 1 =>[...] ...)
//Finally iterate array and execute create
//Service bulk will repeat pattern