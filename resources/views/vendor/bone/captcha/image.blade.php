<img src="{{ $route }}"
    style="cursor:pointer;width:{{ $width }}px;height:{{ $height }}px;"
    title="{{ clean(trans('base::captcha.update_code')) }}"
    onclick="this.setAttribute('src','{{ $route }}?_='+Math.random());var captcha=document.getElementById('{{ $input_id }}');if(captcha){captcha.focus()}"
>
