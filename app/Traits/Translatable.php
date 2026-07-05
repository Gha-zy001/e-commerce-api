<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Relations\HasMany;

trait Translatable
{
  protected array $tempTranslations = [];

  protected static function bootTranslatable(): void
  {
    static::saved(function ($model) {
      if (!empty($model->tempTranslations)) {
        foreach ($model->tempTranslations as $locale => $attributes) {
          $translation = $model->translations()->firstOrNew([
            'language' => $locale,
          ]);
          foreach ($attributes as $key => $value) {
            $translation->{$key} = $value;
          }
          $translation->save();
        }
        $model->tempTranslations = [];
      }
    });
  }

  public function translations(): HasMany
  {
    $translationClass = get_class($this) . 'Translation';
    return $this->hasMany($translationClass, 'id');
  }

  public function getAttribute($key)
  {
    if (is_array($this->translatable) && in_array($key, $this->translatable)) {
      return $this->getTranslation($key);
    }
    return parent::getAttribute($key);
  }

  public function setAttribute($key, $value)
  {
    if (is_array($this->translatable) && in_array($key, $this->translatable)) {
      $locale = app()->getLocale();
      $columnName = $this->getTranslationColumn($key);
      $this->tempTranslations[$locale][$columnName] = $value;
      return;
    }
    return parent::setAttribute($key, $value);
  }

  public function getTranslation(string $key, ?string $locale = null)
  {
    $locale = $locale ?: app()->getLocale();

    if (isset($this->tempTranslations[$locale][$this->getTranslationColumn($key)])) {
      return $this->tempTranslations[$locale][$this->getTranslationColumn($key)];
    }

    $translation = $this->translations->firstWhere('language', $locale);
    if (!$translation) {
      $translation = $this->translations->firstWhere('is_default', true)
        ?? $this->translations->firstWhere('is_default', 1)
        ?? $this->translations->first();
    }
    if ($translation) {
      $columnName = $this->getTranslationColumn($key);
      return $translation->{$columnName};
    }
    return null;
  }

  protected function getTranslationColumn(string $key): string
  {
    if (isset($this->translationColumnMap[$key])) {
      return $this->translationColumnMap[$key];
    }
    return $key;
  }
}
