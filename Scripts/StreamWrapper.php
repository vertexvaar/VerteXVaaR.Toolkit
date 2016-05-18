<?php
if (!in_array('EXT', stream_get_wrappers())) {
    stream_wrapper_register('EXT', \VerteXVaaR\T3Toolkit\ExtensionPathStreamWrapper::class);
}

opendir('EXT://t3toolkit');
