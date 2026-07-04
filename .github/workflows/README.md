# GitHub Actions Workflows

This directory contains automated CI/CD workflows for the Blood Bank API project.

## Workflows

### 1. Tests (`tests.yml`)
**Triggers**: Push to main/develop, Pull Requests

Runs comprehensive test suite and code quality checks:
- ✓ Runs tests on PHP 8.2 and 8.3
- ✓ Sets up MySQL test database
- ✓ Runs unit and feature tests
- ✓ Generates code coverage reports
- ✓ Uploads coverage to Codecov
- ✓ Performs PHP linting
- ✓ Runs Pint code style checks
- ✓ Runs PHPStan static analysis

**Key Steps**:
1. Checkout code
2. Setup PHP and MySQL
3. Install Composer dependencies
4. Configure test environment
5. Run test suite with coverage
6. Generate reports

### 2. Code Quality (`code-quality.yml`)
**Triggers**: Push to main/develop, Pull Requests

Validates code quality and security:
- ✓ PHP syntax validation
- ✓ Pint code style checking
- ✓ PHPStan static analysis (Level 5)
- ✓ Composer security audit
- ✓ OpenAPI specification validation
- ✓ Trivy vulnerability scanning

**Key Checks**:
- PHP linting on all PHP files
- Code style compliance
- Type safety analysis
- Dependency security
- API documentation validity

### 3. Deploy (`deploy.yml`)
**Triggers**: Push to main branch, Manual workflow dispatch

Automated deployment to production:
- ✓ Builds Docker image
- ✓ Pushes to container registry
- ✓ Runs database migrations
- ✓ Collects frontend assets
- ✓ Sends deployment notifications
- ✓ Notifies Slack channel on completion

**Requirements**:
- Docker build environment
- Container registry access
- Database credentials (secrets)
- Slack webhook (optional)

## Setup

### Required Secrets

Add these secrets to your GitHub repository settings (Settings → Secrets and variables → Actions):

```
DB_CONNECTION=mysql
DB_HOST=your-db-host
DB_PORT=3306
DB_DATABASE=bloodbank_prod
DB_USERNAME=db_user
DB_PASSWORD=your_secure_password

DEPLOYMENT_URL=https://api.bloodbank.local
SLACK_WEBHOOK_URL=https://hooks.slack.com/services/YOUR/WEBHOOK/URL
```

### Environment Configuration

For local testing of workflows:

```bash
# Install act (run GitHub Actions locally)
brew install act

# Run a specific workflow
act -j test

# Run all workflows
act
```

## Workflow Details

### Test Workflow Matrix

Tests run on multiple PHP versions:
- PHP 8.2
- PHP 8.3

Database: MySQL 8.0 with health checks

### Code Quality Checks

**Pint** - Laravel code style
- Configuration: `.pint.json`
- Enforces PSR-12 standard

**PHPStan** - Static analysis
- Level: 5 (strict)
- Configuration: `phpstan.neon`

**Composer Audit** - Security
- Checks for known vulnerabilities
- Fails on critical issues

### Deployment Process

1. **Build Phase**
   - Creates Docker image
   - Pushes to GitHub Container Registry
   - Generates metadata and tags

2. **Deploy Phase**
   - Installs production dependencies
   - Generates application key
   - Runs database migrations
   - Collects frontend assets

3. **Notification Phase**
   - Sends Slack notification
   - Updates GitHub deployment status

## Monitoring

### View Workflow Runs

1. Go to Actions tab in GitHub repository
2. Select workflow to view runs
3. Click run to see detailed logs
4. Check specific job output for issues

### Coverage Reports

- Codecov integration for coverage tracking
- Coverage badges can be added to README
- Branch coverage protection can be enabled

## Best Practices

1. **Before Committing**
   ```bash
   # Run tests locally
   php artisan test

   # Check code style
   ./vendor/bin/pint app

   # Static analysis
   ./vendor/bin/phpstan analyse app
   ```

2. **Pull Requests**
   - All workflows must pass before merge
   - Code review required
   - Coverage should not decrease

3. **Production Deployments**
   - Only main branch triggers production deploy
   - Manual override available via workflow_dispatch
   - All tests must pass first

4. **Secrets Management**
   - Never commit secrets to repository
   - Use GitHub Secrets for sensitive data
   - Rotate secrets regularly

## Troubleshooting

### Test Failures

```bash
# Check if migrations are proper
php artisan migrate:status

# Reset test database
php artisan migrate:reset --database=testing
php artisan migrate --database=testing

# Run tests with verbose output
php artisan test --verbose
```

### Docker Build Issues

```bash
# Check Docker configuration
docker buildx version

# Build locally
docker build -t bloodbank-api .

# View build logs
docker buildx build --verbose .
```

### Deployment Failures

1. Check database credentials in secrets
2. Verify container registry access
3. Review application logs on deployment server
4. Check Slack webhook configuration

## Customization

### Adding New Workflows

Create new YAML file in `.github/workflows/`:

```yaml
name: My Workflow

on:
  push:
    branches: [ main ]

jobs:
  my-job:
    runs-on: ubuntu-latest
    steps:
      - uses: actions/checkout@v4
      - name: Step name
        run: command
```

### Modifying Existing Workflows

1. Edit the YAML file directly
2. Changes apply on next push
3. Test with `act` locally first

### Disabling Workflows

Comment out the `on:` section:

```yaml
# on:
#   push:
#     branches: [ main ]
```

## Links

- [GitHub Actions Documentation](https://docs.github.com/en/actions)
- [PHP Actions](https://github.com/shivammathur/setup-php)
- [Docker Actions](https://github.com/docker/build-push-action)
- [act - Run workflows locally](https://github.com/nektos/act)

## Support

For workflow issues:
1. Check GitHub Actions logs
2. Review workflow syntax
3. Verify secrets are configured
4. Check runner logs for system issues
