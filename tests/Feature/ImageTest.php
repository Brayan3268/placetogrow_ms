<?php

namespace Tests\Feature;

use App\Models\Image;
use Tests\TestCase;

class ImageTest extends TestCase
{
    public function testImage(): void
    {
        /*$image = new Image([
            'name' => 'test-image.jpg',
            'mime_type' => 'image/jpg',
            'image_data' => 'base64_encoded_image_data_here',
        ]);

        $this->assertInstanceOf(Image::class, $image);
        $this->assertEquals('test-image.jpg', $image->name);
        $this->assertEquals('image/jpg', $image->mime_type);
        $this->assertEquals('base64_encoded_image_data_here', $image->image_data);

        $this->assertFile($image);*/

        // Crear una nueva instancia de Image
        $image = new Image;

        // Obtener los atributos fillable del modelo
        $fillable = $image->getFillable();

        // Verificar que los atributos fillable sean los esperados
        $expectedFillable = ['name', 'mime_type', 'image_data'];
        $this->assertEquals($expectedFillable, $fillable);
    }
}
