<?php

namespace App\Models;

use Slim\Http\UploadedFile;
use Illuminate\Database\Eloquent\Model;
use Ramsey\Uuid\Uuid;
use App\Unoconv\Unoconv;

class Attachment extends Model
{
    use Traits\HasAudit;

    protected $table = 'attachment';
    protected $fillable = [
        'id',
        'name',
        'extension',
        'type',
        'size'
    ];
    public $incrementing = false;

    // Relations

    public function attachable()
    {
        return $this->morphTo('model', 'model_type', 'model_id');
    }

    // Attributes

    public function getNameWithExtensionAttribute()
    {
        return "{$this->name}.{$this->extension}";
    }

    public function getPathAttribute()
    {
        return sprintf('%s/%s', FILES_PATH, $this->id);
    }

    public function getPdfPathAttribute()
    {
        return sprintf('/documents/%s__preview.pdf', $this->id);
    }

    public function getHasPdfAttribute()
    {
        return file_exists(PUBLIC_PATH . $this->pdf_path);
    }

    // Functions

    public function fillFromUploadedFile(UploadedFile $file)
    {
        $info = pathinfo($file->getClientFilename());

        if (!$this->id) {
            $this->id = Uuid::uuid4()->toString();
        }

        $this->name      = isset($info['filename'])? $info['filename']: null;
        $this->extension = isset($info['extension'])? $info['extension']: null;
        $this->type      = $file->getClientMediaType();
        $this->size      = $file->getSize();
        $this->preview   = false;
        $this->pages     = 0;
    }

    public function upload(UploadedFile $file)
    {
        $this->fillFromUploadedFile($file);

        if (file_exists($this->path)) {
            unlink($this->path);
        }

        $file->moveTo($this->path);
    }

    public function unlink()
    {
        if (file_exists($this->path)) {
            unlink($this->path);
        }
    }

    public function createPdf(Unoconv $unoconv)
    {
        $pdfPath = PUBLIC_PATH . $this->pdf_path;
        $unoconv->convertToPdf($this->path, $pdfPath);
    }
}
