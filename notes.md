User
- id
- name
- email
- password

Customer
- id
- user_id
- phone_number
- address

Staff
- id
- user_id
- position
- phone_number
- salary
- address

Supplier
- id
- user_id
- company_name
- phone_number
- address

Brand
- id
- name
- description
- supplier_id

Category
- id
- name
- design
- description

Cart /
- id
- customer_id
- total_amount

Product
- id
- code
- name
- category_id
- color
- brand_id (foreign)
- unit_selling_price
- unit_buying_price
- quantity
- minimum_required_quantity
- status

Product Cart
- id
- product_id (foreign)
- cart_id (foreign)
- quantity

Sale
- id
- staff_id (foreign)
- customer_id (foreign)
- date
- total_amount
- status (['ordered', 'approved', 'delivered'])
- description

Sale Detail
- id
- sale_id (foreign)
- product_id (foreign)
- quantity
- total_amount

Purchase
- id
- invoice_number
- supplier_id (foreign)
- staff_id (foreign)
- product_id (foreign)
- unit_selling_price
- unit_buying_price
- payment_type
- quantity
- status
- description

Product Transaction xxx
- id
- product_id (foreign)
- date
- quantity
- model_type ('Order', 'Purchase')
- reference_id

Payment
- total_amount
- model_type ('Sale', 'Purchase', 'Delivery')
- reference_id
- status (['completed']) xxx
- account_name
- account_number

Delivery
- sale_id
- address
- status (['pending', 'delivered'])
- description


purchase CR
staff
customer
dashboard
product page (customer page)
sale
deliver
add to cart