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
Laravel<br>
Sanctum<br>
MySQL<br>

## Authentication Flow
Register → OTP verify → Set password → Login<br>

## Authorization
Protected routes require Bearer token via Sanctum.<br>

## Setup
composer install<br>
copy .env<br>
php artisan key:generate<br>
php artisan migrate<br>

### payment setup
STRIPE_KEY=pk_test_...<br>
STRIPE_SECRET=sk_test_...<br>
STRIPE_WEBHOOK_SECRET=whsec_...<br>

## API Endpoints

### Auth
POST /api/register  
POST /api/verify-otp  
POST /api/set-password  
POST /api/login  
POST /api/logout (protected)

### Profile Endpoints

### Customer
POST /customer-profile  
PATCH /customer-profile  
GET /customer-profile 

### Bookings Endpoints for Customer
POST /customer-profile/bookings  
PATCH /customer-profile/bookings/{id}/accept  
PATCH /customer-profile/bookings/{id}/reject 

### Provider
POST /provider-profile  
PATCH /provider-profile  
GET /provider-profile

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

### Provider Approve
POST /admin/provider/{id}/approve


