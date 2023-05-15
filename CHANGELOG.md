# Changelog

All notable changes of wp-api are documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [Unreleased]

------------------

## [1.1.0] - 2023-05-15

### Fixed
* Fixed some issues with bad references for coupon keys.

### Changed
* Fixed not returning the full shipping reference with instnace id for order shipping lines.

### Added
* Added filters for the full order line and cart line data when we are setting them in our Data classes. For example cart line items can now be filtered using the `cart_set_line_items` filter. Thank you [@fitimvata](https://github.com/fitimvata)!

## [1.0.3] - 2023-03-02

### Fixed

* Fixed getting the applied coupon amount from smart coupons instead of the coupon total amount.

## [1.0.2] - 2023-02-23

### Fixed

* Fixed an issue trying to calculate shipping lines without shipping being set.

## [1.0.1] - 2023-02-22

### Fixed

* Fixed support for Cart fees.

## [1.0.0] - 2023-01-13

### Added

* Initial release of the package.
