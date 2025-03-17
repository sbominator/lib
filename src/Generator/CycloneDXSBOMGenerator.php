<?php

namespace SBOMinator\Lib\Generator;

use SBOMinator\Lib\Dependency;

class CycloneDXSBOMGenerator
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
     * Generates a valid CycloneDX SBOM in JSON format.
     *
     * The method traverses the dependency tree recursively to:
     * - Build a list of unique components.
     * - Create a mapping of component dependencies.
     *
     * The final SBOM includes:
     * - "bomFormat": set to "CycloneDX"
     * - "specVersion": the CycloneDX specification version (here "1.4")
     * - "version": a BOM version (here 1)
     * - "components": an array of all components (libraries) in the tree
     * - "dependencies": an array mapping each component’s "bom‑ref" to its direct dependencies
     *
     * @return string The generated SBOM as a JSON string.
     */
    public function generate(): string
    {
        $components = [];
        $dependencyMap = [];
        $visited = [];

        // Helper: recursively traverse a dependency tree.
        $traverse = function (Dependency $dep) use (&$traverse, &$components, &$dependencyMap, &$visited) {
            // Create a unique reference for the component (using name and version).
            $key = $this->getComponentRef($dep);

            // If we have not yet visited this dependency, add it to our components list.
            if (!isset($visited[$key])) {
                $visited[$key] = true;
                $components[$key] = [
                    "bom-ref" => $key,
                    "type" => "library", // most dependencies are libraries
                    "name" => $dep->getName(),
                    "version" => $dep->getVersion(),
                ];
            }

            // Process all direct subdependencies.
            $childRefs = [];
            foreach ($dep->getDependencies() as $child) {
                $childKey = $this->getComponentRef($child);
                $childRefs[] = $childKey;
                // Recursively traverse the subdependency.
                $traverse($child);
            }

            // If this dependency has children, add a dependency mapping.
            if (!empty($childRefs)) {
                if (isset($dependencyMap[$key])) {
                    // Merge with existing child dependencies and remove duplicates.
                    $dependencyMap[$key] = array_unique(array_merge($dependencyMap[$key], $childRefs));
                } else {
                    $dependencyMap[$key] = $childRefs;
                }
            }
        };

        // Traverse each top-level dependency in the tree.
        foreach ($this->dependencyTree as $dep) {
            $traverse($dep);
        }

        // Convert the dependency map to the format expected by CycloneDX.
        $dependencies = [];
        foreach ($dependencyMap as $ref => $dependsOn) {
            $dependencies[] = [
                "ref" => $ref,
                "dependsOn" => $dependsOn,
            ];
        }

        // Assemble the final SBOM array.
        $sbom = [
            "bomFormat" => "CycloneDX",
            "specVersion" => "1.4",
            "version" => 1,
            "components" => array_values($components), // reindex for JSON array
            "dependencies" => $dependencies,
        ];

        // Return the SBOM as a prettified JSON string.
        return json_encode($sbom, JSON_PRETTY_PRINT);
    }

    /**
     * Generates a unique component reference for a Dependency.
     *
     * In this implementation, the "bom-ref" is the combination of the dependency name and version.
     *
     * @param Dependency $dep The dependency object.
     *
     * @return string The unique component reference.
     */
    private function getComponentRef(Dependency $dep): string
    {
        return $dep->getName() . '|' . $dep->getVersion();
    }
}
