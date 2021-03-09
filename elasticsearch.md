```
 PUT mylane_road
 {
   "mappings": {
     "properties": {
       "db_id": {
         "type": "integer"
       },
      "fcm_token": {
         "type": "text"
       },
       "s_place": {
         "type": "text"
       },
       "s_location": {
         "type": "geo_point"
       },
       "e_place": {
         "type": "text"
       },
       "e_location": {
         "type": "geo_point"
       },
       "resource_id": {
         "type": "integer"
       },
       "status": {
         "type": "integer"
       },
       "user_id": {
         "type": "integer"
       },
       "mode": {
         "type": "text"
       },
       "created_at": {
         "type": "date"
       },
       "steps": {
       "type":"geo_shape"
       }
     }
   }
 }
```
