<?php

namespace SBOMinator\Lib\Generator;

use SBOMinator\Lib\Dependency;

class SpdxSBOMGenerator
{
    /**
     * The dependency tree (an array of Dependency objects).
     *
     * @var Dependency[]
     */
    private array $dependencyTree;

    /**
     * Constructor.
     *
     * @param Dependency[] $dependencyTree The top-level dependency tree.
     */
    public function __construct(array $dependencyTree)
    {
        $this->dependencyTree = $dependencyTree;
    }

    /**
     * Generates a valid SPDX SBOM in JSON format.
     *
     * This method recursively traverses the dependency tree to:
     * - Build a list of unique packages.
     * - Create a list of relationships indicating which package depends on which.
     *
     * The resulting SPDX document includes:
     * - "spdxVersion": The SPDX version.
     * - "dataLicense": The license under which the SPDX data is released.
     * - "SPDXID": A unique document identifier.
     * - "name": A name for the document.
     * - "creationInfo": Information about who and when the document was created.
     * - "packages": An array of packages with minimal fields.
     * - "relationships": An array of dependency relationships (using the "DEPENDS_ON" relationship type).
     *
     * @return string The generated SPDX SBOM as a prettified JSON string.
     */
    public function generate(): string
    {
        $packages = [];
        $relationships = [];
        $visited = [];

        // Helper closure: recursively traverse the dependency tree.
        $traverse = function (Dependency $dep) use (&$traverse, &$packages, &$relationships, &$visited) {
            $ref = $this->getPackageRef($dep);

            // Add the package if it hasn't been added already.
            if (!isset($visited[$ref])) {
                $visited[$ref] = true;
                $packages[$ref] = [
                    "SPDXID" => $ref,
                    "name" => $dep->getName(),
                    "versionInfo" => $dep->getVersion(),
                    "downloadLocation" => "NOASSERTION",
                    "filesAnalyzed" => false,
                ];
            }

            // Process each direct subdependency.
            foreach ($dep->getDependencies() as $child) {
                $childRef = $this->getPackageRef($child);

                // Record the relationship: parent DEPENDS_ON child.
                $relationships[] = [
                    "spdxElementId" => $ref,
                    "relationshipType" => "DEPENDS_ON",
                    "relatedSpdxElement" => $childRef,
                ];

                // Recursively traverse the subdependency.
                $traverse($child);
            }
        };

        // Traverse each top-level dependency.
        foreach ($this->dependencyTree as $dep) {
            $traverse($dep);
        }

        // Build the final SPDX document array.
        $spdxDocument = [
            "spdxVersion" => "SPDX-2.2",
            "dataLicense" => "CC0-1.0",
            "SPDXID" => "SPDXRef-DOCUMENT",
            "name" => "SBOM",
            "creationInfo" => [
                "creators" => ["Tool: SBOMinator"],
                "created" => date('c'),
            ],
            "packages" => array_values($packages),
            "relationships" => $relationships,
        ];

        // Return the SPDX document as a prettified JSON string.
        return json_encode($spdxDocument, JSON_PRETTY_PRINT);
    }

    /**
     * Generates a unique SPDX package reference for a Dependency.
     *
     * In this implementation, the SPDXID is built from the dependency's name and version.
     * Characters that might be problematic (such as slashes or spaces) are replaced.
     *
     * @param Dependency $dep The dependency object.
     * @return string The unique SPDX package reference.
     */
    private function getPackageRef(Dependency $dep): string
    {
        // Replace '/' and ' ' with '-' to form a valid SPDXID.
        $safeName = str_replace(['/', ' '], '-', $dep->getName());
        return "SPDXRef-" . $safeName . '|' . $dep->getVersion();
    }
}
