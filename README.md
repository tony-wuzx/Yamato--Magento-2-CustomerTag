# Yamato--Magento-2-CustomerTag
 
### Core Feature
* add a multiselect attribute called `tags` to customer entity
    - manager `tags` in store configuration tab called `Yamato`
    - update `tags` source options via store configuration save event
    - get `customer_tags` in `extension_attributes` of API (`GET /V1/store/storeConfigs`)
    - update `tags` via custom attribute of API (`POST /V1/customers/me`)
    - assign `tags` to customer in customer form in back office
    - add `tags` column and multiselect filter to customer grid
    - add `customer_tags` column and multiselect filter to order grid