# Service Booking API

## Overview
Quotation based service booking API with OTP authentication and role based workflow.

## Features
- OTP authentication  
- Customer and provider roles  
- Provider quotation system  
- Booking lifecycle management  
- Stripe payment integration (planned)  
- Customer profile management  
- Provider profile with admin verification flag  
- Role based route protection  
  
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

### Provider
POST /provider-profile  
PATCH /provider-profile  
GET /provider-profile

### Service 
POST /provider/services  
PATCH /provider/services/{id} 
GET /provider/services
DELETE /provider/services/{id}

### Admin

### Provider Approve
POST /admin/provider/{id}/approve