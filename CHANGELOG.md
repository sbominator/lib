# Changelog

## [0.4.0](https://github.com/sbominator/lib/compare/v0.3.0...v0.4.0) (2025-03-17)


### Features

* add dependency graph generator ([77898f3](https://github.com/sbominator/lib/commit/77898f3475af044eb7eef34ba35da8e964a5a58d))
* add rudimentary generator of sboms from dependency tree ([f660f4b](https://github.com/sbominator/lib/commit/f660f4b7d7d5e76324f03f4064cc48b4ba204e73))
* **FileScanner:** add deduplication of dependencies ([c657c2e](https://github.com/sbominator/lib/commit/c657c2ef123736709d7d342f76dab9011eeb9c58))


### Bug Fixes

* use less ambigous namespace ([#12](https://github.com/sbominator/lib/issues/12)) ([2cc87dd](https://github.com/sbominator/lib/commit/2cc87dd44adf752fe3c6cbff8cb303501be4b2d8))
* use proper SBOMinator\Lib PSR-4 namespace ([#15](https://github.com/sbominator/lib/issues/15)) ([11604c4](https://github.com/sbominator/lib/commit/11604c4ad2d23407ddb3adeccd5d8024683a41ab))


### Miscellaneous Chores

* only SemVer relevant sections in changelog ([fdba2ed](https://github.com/sbominator/lib/commit/fdba2ed3b4bfe4aea52e38c42176f839f705da29))
* only SemVer relevant sections in changelog ([e5b45c9](https://github.com/sbominator/lib/commit/e5b45c9722f9fc6c5d167412c12de7840afc7cd1))

## [0.3.0](https://github.com/sbominator/lib/compare/v0.2.2...v0.3.0) (2025-03-16)


### Features

* add FileScanner for scanning directories and combining the dependencies ([ed82754](https://github.com/sbominator/lib/commit/ed82754878c3e3db62d520156c99e153510bf80b))

## [0.2.2](https://github.com/sbominator/lib/compare/v0.2.1...v0.2.2) (2025-03-16)


### Bug Fixes

* add licensing info ([a0acd72](https://github.com/sbominator/lib/commit/a0acd726ad9e673c0ecf45d47aa6b160133d8385))

## [0.2.1](https://github.com/sbominator/lib/compare/v0.2.0...v0.2.1) (2025-03-16)


### Bug Fixes

* **NpmParser:** remove top level dependency filter ([ea309a1](https://github.com/sbominator/lib/commit/ea309a1e728ee7195f5b7996a881b801e2c305ea))


### Miscellaneous Chores

* remove unused release stuff ([43f5b0d](https://github.com/sbominator/lib/commit/43f5b0dc3ed2e777c9e6ae163e735dd5dd4324d7))
* set minimum PHP requirement to v8.2 ([622a87c](https://github.com/sbominator/lib/commit/622a87cf67aaac3bb866d1c03e6834e3e87c57da))

## [0.2.0](https://github.com/sbominator/lib/compare/v0.1.0...v0.2.0) (2025-03-16)


### Features

* release initial version ([58e8a16](https://github.com/sbominator/lib/commit/58e8a168d9deae1fa32ce0bac8fa91ae276ba155))


### Bug Fixes

* logic error ([6374c2f](https://github.com/sbominator/lib/commit/6374c2fd80968701b2ade540bcb3d5dac6acada9))
* README had withoutDevPackages() option in the wrong spot ([33032b9](https://github.com/sbominator/lib/commit/33032b933c191cece7af2e7da94194703f2939e9))
* README typo ([167523c](https://github.com/sbominator/lib/commit/167523c671feec8c2e2ad8435b145895441d4040))


### Miscellaneous Chores

* change name to lib ([36697ae](https://github.com/sbominator/lib/commit/36697aefad07ac417a3e4a1563f1a09810be1a1f))
* change name to sbom-lib ([7b4bd1d](https://github.com/sbominator/lib/commit/7b4bd1d0e7e37f42e6160ed34db9a832e1b06151))
* no chores in changelog ([0df884f](https://github.com/sbominator/lib/commit/0df884f0bc7df636c2f0993b04ee3a84f81dc5f3))
* update package name ([83a62f5](https://github.com/sbominator/lib/commit/83a62f5a1a0179234ad9f6797d385f416c1e064a))

## [0.1.0](https://github.com/sbominator/package/compare/v0.1.0...v0.1.0) (2025-03-16)


### Features

* initial version ([41b5f28](https://github.com/sbominator/package/commit/41b5f287bb4c69de82e6e720ae4c4cbc4708abb0))
* initial version ([af4abcd](https://github.com/sbominator/package/commit/af4abcd57dfb0653f8cbeda2dad87d909eb205ab))

## 0.1.0 (2025-03-16)


### Features

* **cli:** add minicli to have an interface for the lib while developing ([332d2a6](https://github.com/sbominator/package/commit/332d2a6be390f9f4bf044bb5d5fb1ef779bc92fa))
* initial version ([c816daa](https://github.com/sbominator/package/commit/c816daac452768d97e4e239da7e9e9d1568f4766))
* **lib:** add first sbominator library class and add it to the composer autoloader ([2d243a0](https://github.com/sbominator/package/commit/2d243a03c367fede22c0be669e0dbd18491177c7))
* **lib:** first working proof of concept for composer.lock, package-lock.json, SPDX-SBOMs and CycloneDX-SBOMs ([feca04b](https://github.com/sbominator/package/commit/feca04bcf2c9414dea03f71367ec708af44c5dd6))


### Bug Fixes

* **composer:** fix another typo in composer.json ([ccbbdf0](https://github.com/sbominator/package/commit/ccbbdf07a0122c2fa3a16dc206b661dc516abd7c))
* **composer:** fix typo in composer.json ([3457c5e](https://github.com/sbominator/package/commit/3457c5e5f68d64aee90fe5f1210e58dd355cdb9f))


### Miscellaneous Chores

* add initial version ([cccc6d2](https://github.com/sbominator/package/commit/cccc6d28d69be35c09ce0c3abdf23506d272c3bd))
* add version to composer.json ([c823580](https://github.com/sbominator/package/commit/c8235802573a34771517d7410b440f8d303ecfde))
* **dev-env:** add ddev environment for simple development across machines ([682297f](https://github.com/sbominator/package/commit/682297f7082e97df1dc2d5ce2a9a20709ce7552d))
* **dev-env:** change php version to 8.4 ([ff9e14b](https://github.com/sbominator/package/commit/ff9e14b0f4bf91062b4049b876b87b9675167bd6))
* **docs:** fix typo in README ([e6c074a](https://github.com/sbominator/package/commit/e6c074a9b9ae69d8439363a33cc71c1edaf04ecc))
* **documentation:** add a simple README ([214e601](https://github.com/sbominator/package/commit/214e6019023ae6c02659a9ff3a96c94f9426fbbf))
* **documentation:** add dev documentation to README ([c4da676](https://github.com/sbominator/package/commit/c4da676835203c194394fffbd5853aff0356ae8a))
* manifest version to 0.0.1 ([c3da71d](https://github.com/sbominator/package/commit/c3da71d03c015dd8c134b0dcf3c74262eea70d65))
* re-structure readme ([144c3bf](https://github.com/sbominator/package/commit/144c3bfca09becc245293fbed61662f5a7a7ea68))
* **sync:** push work in progress ([2a6ea5b](https://github.com/sbominator/package/commit/2a6ea5bd854b5f265f6768c8ec1059a689240b16))
* **sync:** push work in progress ([069280e](https://github.com/sbominator/package/commit/069280e1fe5519aea3979600aa434e4327fcfa05))
