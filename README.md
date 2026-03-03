# Service Booking API

## Overview
Quotation based service booking API with OTP authentication and role based workflow.

## Features
- OTP authentication  
- Customer and provider roles  
- Provider quotation system  
- Booking lifecycle management  
- Customer profile management  
- Provider profile with admin verification flag  
- Role based route protection  
- Stripe Checkout integration
- Webhook based payment confirmation
- Async payment verification

## Tech Stack
Laravel   
Sanctum   
MySQL   

## Authentication Flow
Register → OTP verify → Set password → Login   

## Authorization
Protected routes require Bearer token via Sanctum.   

## Setup
composer install  
copy .env  
php artisan key:generate  
php artisan migrate  

### payment setup
STRIPE_KEY=pk_test_...   
STRIPE_SECRET=sk_test_...  
STRIPE_WEBHOOK_SECRET=whsec_...  

## API Endpoints

### Auth
POST /api/register  
POST /api/verify-otp  
POST /api/set-password  
POST /api/login  
POST /api/logout (protected)

### Customer Profile Endpoints   
POST /customer-profile  
PATCH /customer-profile  
GET /customer-profile 

### Bookings Endpoints for Customer
POST /customer-profile/bookings  
PATCH /customer-profile/bookings/{id}/accept  
PATCH /customer-profile/bookings/{id}/reject  

### Provider Profile Endpoints
POST /provider-profile  
PATCH /provider-profile  
GET /provider-profile

### Provider Profile Details For Users
GET /provider-profile/{id}

### Bookings Endpoints for Provider
PATCH /provider-profile/bookings/{id}/quote

### Service 
POST /provider/services     
PATCH /provider/services/{id}     
GET /provider/services  
DELETE /provider/services/{id}  

### payment EndPoints
GET /api/customer-profile/bookings/{id}/pay  
POST /api/stripe/webhook (internal use)

### Admin

### Admin Endpoints
GET    /api/admin/providers/pending  
PATCH  /api/admin/providers/{id}/approve  
PATCH  /api/admin/providers/{id}/reject  
GET    /api/admin/bookings  
GET    /api/admin/payments  
GET    /api/admin/dashboard-stats   


