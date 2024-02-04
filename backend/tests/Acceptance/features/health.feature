Feature:
    In order to prove that backend is up and ready
    As a user
    I want to check api health

    Scenario: It receives proper response from health endpoint
        When a demo scenario sends a request to "/api/public/health"
        Then a "200" status code should be received
