# Security Policy

## Reporting a Vulnerability

The Astroxs team takes security seriously. We appreciate your efforts to responsibly disclose your findings.

### How to Report

**Please do not report security vulnerabilities through public GitHub issues.**

Instead, please send an email to security@astroxs.dev (or create a private security advisory on GitHub).

Include as much information as possible:
- Type of vulnerability
- Full paths of source files related to the issue
- Location of the affected code
- Step-by-step instructions to reproduce the issue
- Proof-of-concept or exploit code (if possible)
- Impact of the vulnerability

### What to Expect

- We will acknowledge your email within 48 hours
- We will provide a more detailed response within 7 days
- We will work with you to understand and resolve the issue
- We will publicly disclose the issue after a fix is released

### Security Best Practices

When using Astroxs:

1. **Change default credentials immediately** after installation
2. **Use strong passwords** for all user accounts
3. **Enable HTTPS** in production environments
4. **Keep dependencies updated** (composer update)
5. **Disable commands in production** by setting `ASTROXS_DISABLE_COMMANDS=true`
6. **Use environment variables** for sensitive configuration
7. **Implement rate limiting** on authentication endpoints
8. **Regular security audits** of your user roles and privileges
9. **Monitor suspension logs** for unusual activity
10. **Rotate tokens regularly** for high-privilege accounts

### Supported Versions

| Version | Supported          |
| ------- | ------------------ |
| 1.x     | :white_check_mark: |

## Security Features

Astroxs includes several security features:

- Laravel Sanctum token authentication
- Automatic token revocation on suspension
- Protected roles that cannot be deleted
- Role and privilege caching for performance
- Audit logging middleware
- Secure password hashing (bcrypt)
- CSRF protection (via Laravel)
- SQL injection prevention (via Eloquent)

Thank you for helping keep Astroxs and its users safe!
