I have implemented a highly secure session management system for the admin panel, including:

1. Secure Session Defaults: SessionMiddleware now enforces secure cookie parameters such as httponly, samesite=Lax, and
   secure (when HTTPS is available).
2. Anti-Session Fixation: The createSession method in UserAction calls session_regenerate_id(true) upon successful login
   to prevent fixation attacks.
3. Identity Verification: Sessions now store and validate the user's IP address and User Agent. IsAdminMiddleware
   verifies these on every request to prevent session hijacking.
4. Secure Authentication: User passwords in data.json are now stored as secure hashes, and password_verify is used during
   login.
5. Robust Error Handling: The login view was updated to securely display authentication failures.
6. Full Lifecycle: Added a secure /logout route and method to properly destroy sessions.
