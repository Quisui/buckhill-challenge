# Challenge

# Docker file

I've build a docker file that will contain all the app, as well a set of automated instructions for the docker compose file:
1) Dockerfile
2) docker-compose.yml
3) docker/config - start.sh

# CI/CD

As you can see i've done automated deployments and testing on my repo, so in case that we need to push the image to production anything will be tested before the push, if a test fails the whole deployment will not be deployed. (github actions = .github/workflows)
