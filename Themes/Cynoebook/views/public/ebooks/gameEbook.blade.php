@extends('public.layout')

@section('title')
    @if (request()->has('query'))
        {{ clean(trans('cynoebook::ebooks.search_results_for')) }}: "{{ request('query') }}"
    @else
        {{ clean(trans('cynoebook::ebooks.ebooks')) }}
    @endif
@endsection

@section('breadcrumb')
    @if (request()->has('query') || request()->has('category'))
        <li><a href="{{ route('ebooks.index') }}">{{ clean(trans('cynoebook::ebooks.ebooks')) }}</a></li>
        
        @if(request()->has('category'))
            @if(request()->has('query'))
                <li><a href="{{ route('ebooks.index', ['category' => request('category')]) }}">{{ request('category') }}</a></li>
            @else
                <li class="active">{{ request('category') }}</li>
            @endif
        @endif
        
        @if(request()->has('query'))
            <li class="active">{{clean(trans('cynoebook::ebooks.search_results_for')) }}:{{ request('query') }}</li>
        @endif
        
        
    @else
        <li class="active">{{ clean(trans('cynoebook::ebooks.ebooks')) }}</li>
    @endif
    
    
    
@endsection

@section('content')
    <section class="gameEbook_section" style="padding: 10px">
        <div class="row m-b-20">
            <div class='col-md-8'>
                <div class="row m-b-20">
                    <h2>{{$ebook_title}}</h2>
                </div>
                
                @if(count($current_episode)>0)
                    <div class="row m-b-20" style="min-height: 45vh">
                        <div style="line-height: 2">{!! $current_episode['description']!!}</div>
                    </div>
                    @if( $current_episode['has_dice'])
                        <div class="row m-b-20">
                            <div style="width: 20%;margin: auto;border: 1px solid #4e80bf;padding: 10px;background-color: #ecf1f7;">
                                <div class="dice__scene"  style="margin: auto">
                                    <div id="dice__cube" class="show-front">
                                        <div class="dice__side dice__side--front"  data_num ='1'></div>
                                        <div class="dice__side dice__side--back"   data_num ='6'></div>
                                        <div class="dice__side dice__side--right"  data_num ='4'></div>
                                        <div class="dice__side dice__side--left"   data_num ='3'></div>
                                        <div class="dice__side dice__side--top"    data_num ='2'></div>
                                        <div class="dice__side dice__side--bottom" data_num ='5'></div>
                                    </div>
                                </div> 
                            </div>
                        </div>
                        <div class="row m-b-20" id="Dice_Button" style="text-align:center">
                            <button type="submit" class="btn btn-primary btn-sm" id="dice__btn" style="width:60%;white-space: initial;" > Roll Dice </button>
                        </div>

                        <div class="row m-b-20" style="text-align:center;display: none;" id="Dice_link">
                            @foreach ($next_episodes as $next_link)
                                <form method="POST" action="{{ route('ebooks.gameRun') }}" @if($next_link['is_even']) id="is_even" @else id="is_odd" @endif>
                                    {{ csrf_field() }}
                                    <input type="hidden" name="gameToken" value="{{$gameToken}}">
                                    <input type="hidden" name="next_episode_id" value="{{$next_link['next_episode_id']}}">
                                    <input type="hidden" name="ebook_id" value="{{$ebook_id}}">
                                    <input type="hidden" name="ebook_title" value="{{$ebook_title}}">
                                    <button type="submit" class="btn btn-primary btn-sm" style="width:60%;white-space: initial;"> {{$next_link['text']}}</button>
                                </form>
                            @endforeach
                        </div>
                    
                        @push('scripts')
                        <script src="{{ Theme::url('public/js/dice.js') }}"></script>
                        @endpush
                    @elseif ( $current_episode['is_last'])
                        
                        @push('scripts')
                            <script>
                                $(document).ready(function(){
                                   $("#finish_page").modal("show");
                                })
                            </script>
                        @endpush
                    @else
                        @foreach ($next_episodes as $next_link)
                        <div class="row m-b-20" style="text-align: center">
                            <form method="POST" action="{{ route('ebooks.gameRun') }}">
                                {{ csrf_field() }}
                                <input type="hidden" name="gameToken" value="{{$gameToken}}">
                                <input type="hidden" name="next_episode_id" value="{{$next_link['next_episode_id']}}">
                                <input type="hidden" name="ebook_id" value="{{$ebook_id}}">
                                <input type="hidden" name="ebook_title" value="{{$ebook_title}}">
                                <button type="submit" class="btn btn-primary btn-sm" style="width:60%;white-space: initial;"> {{$next_link['text']}}</button>
                            </form>
                        </div>
                        @endforeach
                        
                    @endif
                @endif
            </div>
            <div class='col-md-4'>
                <div class="card" style="border: 1px solid #4e80bf; margin-bottom:10px">
                    <div class="card-header" style="padding: 10px;background-color: #4e80bf;">
                        <div class="d-flex align-items-center">
                            <h4 class="card-title" style="color: white;">{{ clean(trans('ebook::ebooks.form.items_table.title')) }}</h4>
                        </div>
                    </div>
                    <div class="card-body" style="padding: 10px">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th></th>
                                    <th>name</th>
                                    <th>value</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($gamehasItems as $items)
                                    <tr>
                                        <td>
                                            <div class="avatar-holder">
                                                @if (is_null($items['path']))
                                                    <i class="fas fa-image"></i>
                                                @else
                                                    <img src="{{  Storage::url($items['path']) }}" width="90px" height="100px">
                                                @endif
                                            </div>
                                        </td>
                                        <td>{{$items['item_name']}}</td>
                                        <td>{{$items['value']}}</td>
                                   
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card" style="border: 1px solid #4e80bf;margin-bottom: 10px">
                    <div class="card-header" style="padding: 10px;background-color: #4e80bf;">
                        <div class="d-flex align-items-center">
                            <h4 class="card-title" style="color: white;">{{ clean(trans('ebook::ebooks.form.skills_table.title')) }}</h4>
                        </div>
                    </div>
                    <div class="card-body" style="padding: 10px">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>name</th>
                                    <th>value</th>
                                </tr>
                            </thead>
                            <tbody>
                                
                                @foreach ($gamehasSkills as $skills)
                                    <tr>
                                        <td>{{$skills['skill_name']}}</td>
                                        <td>{{$skills['value']}}</td>
                                   
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card" style="border: 1px solid #4e80bf;margin-bottom: 10px">
                    <div class="card-header" style="padding: 10px;background-color: #4e80bf;">
                        <div class="d-flex align-items-center">
                            <h4 class="card-title" style="color: white;">{{ clean(trans('ebook::ebooks.form.codewords_table.title')) }}</h4>
                        </div>
                    </div>
                    <div class="card-body" style="padding: 10px">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>name</th>
                                    <th>value</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($gamehasCodewords as $codewords)
                                    <tr>
                                        <td>{{$codewords['codeword_name']}}</td>
                                        <td>{{$codewords['value']}}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <div class="modal fade In" id="finish_page" role="dialog" >
        <div class="modal-dialog modal-sm" style="margin-top: 25vh">
          <!-- Modal content-->
          <div class="modal-content">
          
            <div class="modal-body" style="text-align: center">
                <pre style="background-color: transparent">

                    <h2><span style="color: red">Congratulations!</span></h2>
                            <h3> You've played his book!</h3>

                    <h4> You can re-play this book from the begining </h4>

                          <h4>  Or choose another one.</h4>
                </pre>
            </div>
            <div class="modal-footer" style="border-top: none;">
                @if(count($current_episode)>0)
                    @if ( $current_episode['is_last'])
                        <div class="row m-b-20" style="text-align: center">
                            <div class="col-sm-6">
                                <form method="POST" action="{{ route('ebooks.gameRun') }}" >
                                    {{ csrf_field() }}
                                    <input type="hidden" name="gameToken" value="{{$gameToken}}">
                                    <input type="hidden" name="ebook_id" value="{{$ebook_id}}">
                                    <input type="hidden" name="ebook_title" value="{{$ebook_title}}">
                                    <input type="hidden" name="is_final" value="1">
                                    <button type="submit" class="btn btn-primary btn-sm" style="width:70%; margin-top:10px;white-space: initial;"> Read again</button>
                                    
                                </form>
                            </div>
                            <div class="col-sm-6">
                        
                                <form method="POST" action="{{ route('ebooks.gameFinish') }}" >
                                    {{ csrf_field() }}
                                    <input type="hidden" name="ebook_id" value="{{$ebook_id}}">
                                    <input type="hidden" name="ebook_title" value="{{$ebook_title}}">
                                    <button type="submit" class="btn btn-primary btn-sm" style="width:70%;margin-top:10px;white-space: initial;"> Read other ebook</button>
                                </form>
                            
                            </div>
                        </div>
                    @endif
                @endif
            </div>
          </div>
          </form>
        </div>
    </div>
@endsection
 
