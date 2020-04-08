# [Website Project] e-Commerce

<hr />

## /src/db/e-commerce.sql
|Name| Type| Function
|------- | --- | ----
| ads | `Directory`| Visual informations about the project
| ads_images | `Directory`| Contains all website files
| categories | `Directory`| Contains all website files
| users | `Directory`| Contains all website files

### Tables
#### ads
|Name| Type
|------- | --- 
| id | `INT`
| id_user | `INT`
| id_category | `INT`
| state | `TINYINT`
| price | `FLOAT`
| title | `VARCHAR`
| description | `TEXT`

#### ads_images
|Name| Type
|------- | --- 
| id | `INT`
| id_ad | `INT`
| url | `VARCHAR`

#### categories
|Name| Type
|------- | --- 
| id | `INT`
| name | `VARCHAR`

#### users
|Name| Type
|------- | --- 
| id | `INT`
| name | `VARCHAR`
| email | `VARCHAR`
| pass | `VARCHAR`
| phone | `VARCHAR`