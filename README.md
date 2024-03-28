# Healthcare Appointment Booking API Documentation

## Migration and Seeders

To create the necessary tables in the database, run the following command:
```bash
php artisan migrate

Healthcare Appointment Booking API Document

I have used migration for table creation so you need only execute the command
php artisan migrate


Users Table Seeder
Command
To populate the users table with dummy data using the UsersTableSeeder seeder class:
php artisan db:seed --class=UsersTableSeeder
Description
This command will execute the run method defined in the UsersTableSeeder class, seeding the users table with predefined dummy data.

Healthcare Professionals Seeder
Command
To populate the healthcare_professionals table with dummy data using the HealthcareProfessionalsSeeder seeder class:

php artisan db:seed --class=HealthcareProfessionalsSeeder

Description
This command will execute the run method defined in the HealthcareProfessionalsSeeder class, seeding the healthcare_professionals table with predefined dummy data.



User registration and login (with token-based authentication).
User Registration
Endpoint
URL: http://127.0.0.1:8000/api/register
Method: POST
Request Parameters
name: User's name (required)
email: User's email (required, unique)
password: User's password (required, min 8 characters)
Example Request
{
    "name": "divyanshu",
    "email": "divy@mailionator.com",
    "password": "12345678"
}

Example Response
{
    "message": "User registered successfully"
}

User Login
Endpoint
URL: http://127.0.0.1:8000/api/login
Method: POST
Request Parameters
email: User's email (required)
password: User's password (required)
Example Request
{
    "email": "divy@mailionator.com",
    "password": "12345678"
}

Example Response
{
    "token": "10|RdHImYfc7a2o7bFd3u8sqsF67XqQ41bvvcTLF17a1fef2145",
    "message": "Login successfully"
}

Note: After successful login, the user receives an authentication token (token) which needs to be passed in the Authorization header of subsequent API requests using the Bearer token scheme. This token is used to authenticate the user for accessing protected routes.

List all available healthcare professionals.

Healthcare Professionals List
Endpoint
URL: http://127.0.0.1:8000/api/healthcare-professionals
Method: GET
Description
This API endpoint retrieves a list of healthcare professionals available in the system.
Request Headers
None required
Query Parameters
None required
Response
The API returns a JSON response containing a list of healthcare professionals. Each professional object includes the following fields:
id: The unique identifier of the healthcare professional.
name: The name of the healthcare professional.
specialty: The specialty or expertise of the healthcare professional.
Example Response
[
    {
        "id": 1,
        "name": "Dr. John Doe",
        "specialty": "Cardiologist"
    },
    {
        "id": 2,
        "name": "Dr. Jane Smith",
        "specialty": "Dermatologist"
    },
    {
        "id": 3,
        "name": "Dr. Alex Johnson",
        "specialty": "Pediatrician"
    },
    ...
]

Error Responses
If there are no healthcare professionals available, the API may return an empty array.
If there is an error processing the request, the API may return an appropriate error message with the corresponding HTTP status code.
Authentication
Authentication is not required to access this endpoint.
Authorization
Authorization is not required to access this endpoint.
Usage
This endpoint can be used by clients to retrieve a list of healthcare professionals for various purposes, such as booking appointments or viewing profiles.

Book an appointment (should check for availability).

Appointment Booking
Endpoint
URL: http://127.0.0.1:8000/api/appointments
Method: POST
Description
This API endpoint allows users to book appointments with healthcare professionals.
Request Parameters
user_token: The authentication token of the user (required).
healthcare_professional_id: The ID of the healthcare professional (required).
appointment_start_time: The start time of the appointment (required, format: YYYY-MM-DD HH:MM:SS).
appointment_end_time: The end time of the appointment (required, format: YYYY-MM-DD HH:MM:SS).
Example Request Body
{
    "user_token": "10|RdHImYfc7a2o7bFd3u8sqsF67XqQ41bvvcTLF17a1fef2145",
    "healthcare_professional_id": 1,
    "appointment_start_time": "2024-03-24 13:00:00",
    "appointment_end_time": "2024-03-24 14:00:00"
}

Response
Upon successful booking, the API returns a JSON response with the following fields:
message: A success message indicating that the appointment was booked successfully.
appointment: An object containing details of the booked appointment, including:
user_id: The ID of the user who booked the appointment.
healthcare_professional_id: The ID of the healthcare professional for the appointment.
appointment_start_time: The start time of the appointment.
appointment_end_time: The end time of the appointment.
status: The status of the appointment (e.g., "booked").
updated_at: The timestamp when the appointment was last updated.
created_at: The timestamp when the appointment was created.
id: The unique identifier of the appointment.
Example Response
Error Responses
If the request parameters are missing or invalid, the API may return an appropriate error message with the corresponding HTTP status code.
If the appointment cannot be booked (e.g., due to scheduling conflicts), the API may return an error message indicating the reason for the failure.
Authentication
Authentication is required to access this endpoint. Users must provide a valid authentication token (user_token) in the request parameters.
Authorization
Authorization is required to access this endpoint. Users must have the necessary permissions to book appointments.
Usage
Clients can use this endpoint to book appointments with healthcare professionals by providing the required parameters in the request body. Upon successful booking, the API returns details of the booked appointment.


View all appointments for a user.
User Appointments
Endpoint
URL: http://127.0.0.1:8000/api/user-appointments
Method: GET
Description
This API endpoint retrieves all appointments for a specific user.
Request Headers
Authorization: Bearer token for user authentication (required)
Example Request
URL: http://127.0.0.1:8000/api/user-appointments
Method: GET
Headers:
Authorization: Bearer YOUR_TOKEN_HERE
Response
The API returns a JSON response containing an array of appointment objects for the user.
Example Response
{
    "appointments": [
        {
            "id": 1,
            "user_id": 7,
            "healthcare_professional_id": 1,
            "appointment_start_time": "2024-03-24 10:00:00",
            "appointment_end_time": "2024-03-24 12:00:00",
            "status": "booked",
            "created_at": "2024-03-28T14:39:18.000000Z",
            "updated_at": "2024-03-28T14:39:18.000000Z"
        }
    ]
}

Each appointment object in the array contains the following fields:
id: The unique identifier of the appointment.
user_id: The ID of the user who booked the appointment.
healthcare_professional_id: The ID of the healthcare professional for the appointment.
appointment_start_time: The start time of the appointment.
appointment_end_time: The end time of the appointment.
status: The status of the appointment (e.g., "booked").
created_at: The timestamp when the appointment was created.
updated_at: The timestamp when the appointment was last updated.
Error Responses
If the user token is missing or invalid, the API may return an appropriate error message with the corresponding HTTP status code.
Authentication
Authentication is required to access this endpoint. Users must provide a valid Bearer token in the Authorization header.
Authorization
Authorization is required to access this endpoint. Users must be authorized to view their own appointments.
Usage
Clients can use this endpoint to retrieve all appointments for the authenticated user. The API returns an array of appointment objects, which can be displayed or processed as needed by the client application
.
5. Cancel an appointment (with constraints, e.g., not allowed within 24 hours of the appointment time).


Cancel Appointment

Endpoint
URL: http://127.0.0.1:8000/api/cancel-appointment/{appointment_id}
Method: POST
Description
This API endpoint allows users to cancel a specific appointment by providing its ID in the URL.
Request Parameters
None required
URL Parameters
appointment_id: The ID of the appointment to be cancelled (required)
Example Request
URL: http://127.0.0.1:8000/api/cancel-appointment/1
Method: POST
Response
Upon successful cancellation, the API returns a JSON response with a success message.
Example Response
{
    "message": "Appointment cancelled successfully."
}

Error Responses
If the provided appointment ID does not exist or the appointment cannot be cancelled for some reason, the API may return an appropriate error message with the corresponding HTTP status code.
Authentication
Authentication is required to access this endpoint. Users must provide a valid authentication token.
Authorization
Authorization is required to access this endpoint. Users must be authorized to cancel appointments.
Usage
Clients can use this endpoint to cancel a specific appointment by providing its ID in the URL. Upon successful cancellation, the API returns a success message confirming the cancellation.

6. (Optional) Mark an appointment as completed.

Mark Appointment as Completed

Endpoint
URL: http://127.0.0.1:8000/api/complete-appointment/{appointment_id}
Method: POST
Description
This API endpoint allows users to mark a specific appointment as completed by providing its ID in the URL.
Request Parameters
None required
URL Parameters
appointment_id: The ID of the appointment to be marked as completed (required)
Example Request
URL: http://127.0.0.1:8000/api/complete-appointment/1
Method: POST
Response
Upon successful completion, the API returns a JSON response with a success message.
Example Response
{
    "message": "Appointment marked as completed successfully."
}

Error Responses
If the provided appointment ID does not exist or the appointment cannot be marked as completed for some reason, the API may return an appropriate error message with the corresponding HTTP status code.
Authentication
Authentication is required to access this endpoint. Users must provide a valid authentication token.
Authorization
Authorization is required to access this endpoint. Users must be authorized to mark appointments as completed.
Usage
Clients can use this endpoint to mark a specific appointment as completed by providing its ID in the URL. Upon successful completion, the API returns a success message confirming the completion.



