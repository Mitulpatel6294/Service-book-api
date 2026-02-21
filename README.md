# Service Booking API

## Overview
Quotation based service booking API with OTP authentication and role based workflow.

## Features
OTP authentication
Customer and provider roles
Provider quotation system
Booking lifecycle management
Stripe payment integration (planned)

## Tech Stack
Laravel
Sanctum
MySQL

## Authentication Flow
Register → OTP verify → Set password → Login

## Setup
composer install
copy .env
php artisan key:generate
php artisan migrate

## API Endpoints
Register
Verify OTP
Set Password
Login
Logout