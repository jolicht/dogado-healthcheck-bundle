# Dogado Healthcheck Bundle

## Installation

```console
composer require jolicht/dogado-healthcheck-bundle
```

## Configuration


### Enable checks

Activate checks in `config/packages/dogado_healthcheck.yaml`

```yaml
dogado_healthcheck:
  checks:
    - id: dogado_healthcheck.environment_check
    - id: dogado_healthcheck.connection_check
```

### Configure Route

Configurer route in `config/routes/dogado_healthcheck.yaml`

```yaml
healthcheck:
  path: /api/your-component/_/healthz
  methods: GET
  controller: Jolicht\DogadoHealthcheckBundle\Controller\HealthcheckAction
```