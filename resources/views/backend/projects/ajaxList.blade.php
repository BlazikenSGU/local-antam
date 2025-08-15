<div class="table-responsive">
    <table class="table color-table muted-table table-striped" id="grid_table">
        <thead>
            <tr>
                <th>#</th>
                <th style="width: 20px;"><input type="checkbox" class="checkbox-basic check-all">
                </th>
                <th>MS &nbsp;&nbsp;
                    <a href="" title="Tăng dần" data-sort="id_low_high" class="sort {{$sort=='id_low_high'?'active':''}}"><i class="fa fa-long-arrow-up"></i></a>
                    <a href="" title="Giảm dần" data-sort="id_high_low" class="sort {{$sort=='id_high_low'?'active':''}}"><i class="fa fa-long-arrow-down"></i></a>
                </th>
                <th>Tên dự án</th>
                <th>Địa chỉ</th>
                <th>Phường/Xã</th>
                <th>Quận/Huyện</th>
                <th>Tỉnh/TP</th>
                <th>Danh mục</th>
                <th>Trạng thái</th>
                <th>Ngày tạo</th>
            </tr>
        </thead>
        <tbody>
            @forelse($projects as $key => $project)
                <tr>
                    <td>{{++$start}}</td>
                    <td>
                        <input type="checkbox" name="ids[]" value="{{$project->id}}" id="{{$project->id}}" class="checkbox-basic check-all-child">
                    </td>

                    <td>
                        @if(auth()->guard('backend')->user()->can('projects.edit'))
                            <a href="{{route('backend.projects.edit',[$project->id])}}">{{$project->id}}</a>
                        @else
                            {{$project->id}}
                        @endif
                    </td>

                    <td>
                        {{$project->name}}
                    </td>

                    <td>
                        {{$project->address?$project->address:'n/a'}}
                    </td>

                    <td>{{isset($project->ward->name)?str_replace('Phường','P.', $project->ward->name):'n/a'}}</td>
                    <td>{{isset($project->district->name)?str_replace('Quận','Q.', $project->district->name):'n/a'}}</td>
                    <td>{{isset($project->province->name)?str_replace('Thành phố','TP.',$project->province->name):'n/a'}}</td>

                    <td>
                        @foreach($categories as $st)
                            @if($st['id']==$project->category_id)
                                {{$st['name']}}
                                @break
                            @endif
                        @endforeach
                    </td>

                    <td>
                        @foreach($status as $st)
                            @if($st['id']==$project->status)
                                {{$st['name']}}
                                @break
                            @endif
                        @endforeach
                    </td>

                    <td>{{date('H:i d/m/y',strtotime($project->created_at))}}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="12">-</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div style="margin-top: 10px">
    <label> Chọn tất cả <input type="checkbox" class="checkbox-basic check-all"></label>

    @if(auth()->guard('backend')->user()->can('projects.del'))
        <button type="button" class="btn waves-effect waves-light btn-xs btn-danger"
                id="delete_btn">Xóa
        </button>
    @endif
</div>

<br>
<div class="text-center">
    {{ $projects->links() }}
</div>