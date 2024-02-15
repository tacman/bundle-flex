<?php

namespace Yceruto\BundleFlex\Tests\Maker;

use Yceruto\BundleFlex\Composer\CommandRunner;
use Yceruto\BundleFlex\Maker\BundleDirectoryMaker;
use Yceruto\BundleFlex\Maker\BundleFileMaker;
use Yceruto\BundleFlex\Maker\BundleOptions;
use Yceruto\BundleFlex\Template\TemplateFileCreator;

class BundleFileMakerTest extends MakerTestCase
{
    public function testCreateBundleFilesWithCorrectContent(): void
    {
        $maker = new BundleFileMaker(new TemplateFileCreator($this->bundleDir));
        $options = new BundleOptions();
        $options->name = 'acme/foo-bundle';
        $options->hasConfig = true;
        $options->hasWebAssets = true;
        $options->hasTwigTemplates = true;
        $options->hasTranslations = true;
        $options->hasControllers = true;

        $commandRunner = $this->createMock(CommandRunner::class);
        $directoryMaker = new BundleDirectoryMaker($commandRunner, $this->bundleDir);
        $directoryMaker->make($options);

        $maker->make($options);

        $this->assertGenFile('assets/foo-bundle.js');
        $this->assertGenFile('config/routes.php');
        $this->assertGenFile('config/definition.php');
        $this->assertGenFile('public/foo-bundle.min.js');
        $this->assertGenFile('config/services.php');
        $this->assertGenFile('docs/index.md');
        $this->assertGenFile('src/Controller/HelloController.php');
        $this->assertGenFile('templates/hello.html.twig');
        $this->assertGenFile('translations/FooBundle.fr.xlf');
        $this->assertGenFile('README.md');
        $this->assertGenFile('.gitattributes');
    }

    public function testThrowsExceptionWhenUnableToCreateReadmeFile(): void
    {
        $maker = new BundleFileMaker(new TemplateFileCreator('/invalid/path/to/bundle'));
        $options = new BundleOptions();
        $options->name = 'acme/foo-bundle';

        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('Unable to create "/invalid/path/to/bundle/README.md" file.');

        $maker->make($options);
    }
}
