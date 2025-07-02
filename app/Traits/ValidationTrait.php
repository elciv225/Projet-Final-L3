<?php

namespace App\Traits;

trait ValidationTrait
{
    protected array $errors = [];

    /**
     * Valide les données fournies contre un ensemble de règles.
     *
     * @param array $data Les données à valider (ex: $_POST).
     * @param array $rules Les règles de validation. Format: ['nom_champ' => 'regle1|regle2:option']
     *                     Ex: ['email' => 'required|email', 'age' => 'required|numeric|min:18']
     * @return bool True si toutes les validations passent, false sinon.
     */
    protected function validate(array $data, array $rules): bool
    {
        $this->errors = []; // Réinitialiser les erreurs

        foreach ($rules as $field => $ruleString) {
            $value = $data[$field] ?? null;
            $fieldRules = explode('|', $ruleString);

            foreach ($fieldRules as $rule) {
                $ruleName = $rule;
                $ruleOption = null;

                if (str_contains($rule, ':')) {
                    [$ruleName, $ruleOption] = explode(':', $rule, 2);
                }

                $methodName = 'validate' . ucfirst($ruleName);
                if (method_exists($this, $methodName)) {
                    if (!$this->$methodName($field, $value, $ruleOption)) {
                        // La méthode de validation a ajouté une erreur, on peut arrêter pour ce champ ou continuer
                        // Pour l'instant, on continue pour accumuler toutes les erreurs du champ
                    }
                } else {
                    // Gérer le cas où une règle de validation n'existe pas
                    $this->addError($field, "Règle de validation '{$ruleName}' non reconnue.");
                }
            }
        }
        return empty($this->errors);
    }

    protected function addError(string $field, string $message): void
    {
        $this->errors[$field][] = $message;
    }

    public function getErrors(): array
    {
        return $this->errors;
    }

    public function getFirstError(string $field): ?string
    {
        return $this->errors[$field][0] ?? null;
    }

    public function getAllErrorsAsString(): string
    {
        $errorMessages = [];
        foreach ($this->errors as $fieldErrors) {
            foreach ($fieldErrors as $error) {
                $errorMessages[] = $error;
            }
        }
        return implode(" ", $errorMessages);
    }

    // --- Méthodes de validation individuelles ---

    protected function validateRequired(string $field, $value, $option = null): bool
    {
        if (is_null($value) || (is_string($value) && trim($value) === '') || (is_array($value) && empty($value))) {
            $this->addError($field, "Le champ {$field} est obligatoire.");
            return false;
        }
        return true;
    }

    protected function validateEmail(string $field, $value, $option = null): bool
    {
        if (!empty($value) && !filter_var($value, FILTER_VALIDATE_EMAIL)) {
            $this->addError($field, "Le champ {$field} doit être une adresse email valide.");
            return false;
        }
        return true;
    }

    protected function validateNumeric(string $field, $value, $option = null): bool
    {
        if (!empty($value) && !is_numeric($value)) {
            $this->addError($field, "Le champ {$field} doit être numérique.");
            return false;
        }
        return true;
    }

    protected function validateMin(string $field, $value, $option): bool
    {
        if (!is_numeric($option)) {
            // Option pour la règle min doit être numérique
            $this->addError($field, "Option invalide pour la règle min sur le champ {$field}.");
            return false;
        }
        if (is_numeric($value) && $value < $option) {
            $this->addError($field, "Le champ {$field} doit être au minimum {$option}.");
            return false;
        }
        if (is_string($value) && mb_strlen(trim($value)) < $option) {
             $this->addError($field, "Le champ {$field} doit contenir au moins {$option} caractères.");
            return false;
        }
        return true;
    }

    protected function validateMax(string $field, $value, $option): bool
    {
        if (!is_numeric($option)) {
            $this->addError($field, "Option invalide pour la règle max sur le champ {$field}.");
            return false;
        }
        if (is_numeric($value) && $value > $option) {
            $this->addError($field, "Le champ {$field} doit être au maximum {$option}.");
            return false;
        }
        if (is_string($value) && mb_strlen(trim($value)) > $option) {
             $this->addError($field, "Le champ {$field} ne doit pas dépasser {$option} caractères.");
            return false;
        }
        return true;
    }

    protected function validateIn(string $field, $value, $option): bool
    {
        $allowedValues = explode(',', $option);
        if (!in_array($value, $allowedValues)) {
            $this->addError($field, "La valeur du champ {$field} n'est pas autorisée. Valeurs autorisées : " . $option);
            return false;
        }
        return true;
    }

    protected function validateDate(string $field, $value, $option = null): bool
    {
        if (!empty($value)) {
            $d = \DateTime::createFromFormat('Y-m-d', $value);
            if (!($d && $d->format('Y-m-d') === $value)) {
                 $this->addError($field, "Le champ {$field} doit être une date valide au format AAAA-MM-JJ.");
                return false;
            }
        }
        return true;
    }
}
