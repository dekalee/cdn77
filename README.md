Dekalee CDN77
=============

| Service | Badge |
| -------- |:--------:|
| Code quality (scrutinizer) | [![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/dekalee/cdn77/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/dekalee/cdn77/?branch=master) |
| Code coverage (scrutinizer) | [![Code Coverage](https://scrutinizer-ci.com/g/dekalee/cdn77/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/dekalee/cdn77/?branch=master) |
| Build (travis) | [![Build Status](https://travis-ci.org/dekalee/cdn77.svg?branch=master)](https://travis-ci.org/dekalee/cdn77) |

This php library will call the Cdn77 api to perform some actions.

Usage
-----

Each call to the api is wrapped in a `Query` class.

To use this library in a Symfony project, you could use the [cdn77 bundle](https://github.com/dekalee/cdn77-bundle)

Implemented calls
-----------------

- List resources
- Purge resources
- Purge specific file
- Create resource
