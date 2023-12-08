# Price Calculation and Purchase API

This API provides endpoints for calculating the price of a product and making a purchase.

## Calculate Price Endpoint

### Request

```bash
curl -X POST http://127.0.0.1:80/calculate-price \
    -H "Content-Type: application/json" \
    -d '{"product": 1, "taxNumber": "DE123456789", "couponCode": "D15"}
```
```bash
curl -X POST http://127.0.0.1:80/purchase 
    -H "Content-Type: application/json" 
    -d '{"product": 1, "taxNumber": "IT12345678900", "couponCode": "D15", "paymentProcessor": "paypal"}'

