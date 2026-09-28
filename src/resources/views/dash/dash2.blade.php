@extends('layouts.app')

@section('script')
    <script src="{{ asset('js/d3.js') }}"></script>

    <script>
        function handleHorizontalBarChart(data, id) {
            const names = [data[0].name, data[1].name];
            let maxLen = 0;
            names.forEach((name) => {
                const nameArray = [...name];
                let len = 0;
                nameArray.forEach((char, key, array) => {
                    if (char >= 'a' && char <= 'z') {
                        len += 7.9;

                        if (key == 0) {
                            len += 6;
                        }
                        else if (key == array.length - 1) {
                            len += 6;
                        }
                    }
                    else if (char >= 'A' && char <= 'Z') {
                        len += 9.5;

                        if (key == 0) {
                            len += 5;
                        }
                        else if (key == array.length - 1) {
                            len += 5;
                        }
                    }
                    else if (char >= '0' && char <= '9') {
                        len += 9;

                        if (key == 0) {
                            len += 5;
                        }
                        else if (key == array.length - 1) {
                            len += 5;
                        }
                    }
                    else {
                        len += 13.6;

                        if (key == 0) {
                            len += 5;
                        }
                        else if (key == array.length - 1) {
                            len += 5;
                        }
                    }
                })

                if (maxLen < len) {
                    maxLen = len;
                }
            })

            let max = d3.max(data, d => d.value).toString();

            if (max.length < 2) {
                if (Number(max[0]) >= 5) {
                    max = '10';
                }
                else {
                    max = '5';
                }
            }
            else if (max.length === 2) {
                if (Number(max[1]) > 0) {
                    max = `${Number(max[0]) + 1}0`;
                }
            }
            else {
                let _max = '';
                if (Number(max[1]) >= 5) {
                    _max = `${Number(max[0]) + 1}0`;
                }
                else {
                    _max = `${Number(max[0])}5`;
                }

                const len = max.length;
                max = _max;

                for (let i = 0;i < len - 2;i++) {
                    max += '0';
                }
            }

            max = Number(max);

            const maxValue = max;//Math.ceil(d3.max(data, d => d.value)/10)*10;

            const margin = ({top: 35, right: maxValue.toString().length * 11, bottom: 35, left: maxLen})
            const barHeight = 24;
            const width = 350;
            // const height = Math.ceil((data.length + .4) * barHeight) + margin.top + margin.bottom;
            const height = 180;

            // const yAxis = g => g
            //     .attr("transform", `translate(${margin.left},0)`)
            //     .call(d3.axisLeft(y).tickFormat(i => data[i].name).tickSizeOuter(0));

            const x = d3.scaleLinear()
                .domain([0, maxValue])
                .range([margin.left, width - margin.right]);

            // const xAxis = g => g
            //     .attr("transform", `translate(0,${margin.top})`)
            //     .call(d3.axisTop(x).ticks(width / 80, data.format))
            //     .call(g => g.select(".domain").remove());

            const y = d3.scaleBand()
                .domain(d3.range(data.length))
                .rangeRound([margin.top, height - margin.bottom])
                .padding(0.1);

            const format = x.tickFormat(".2");

            const svg = d3.select(id/*'svg'*//*'#graph'*/).append('svg')
                .attr("viewBox", [0, 0, width, height]);

            const svgDefs = svg.append('defs');
            const mainGradient = svgDefs.append('linearGradient')
                .attr('id', 'mainGradient');
            // .attr('gradientTransform', 'rotate(45)');

            mainGradient.append('stop')
                .attr('class', 'stop-left')
                .attr('offset', '0');

            mainGradient.append('stop')
                .attr('class', 'stop-right')
                .attr('offset', '1');

            ///////////////////////////////////////////////////////////////////////////////////////
            // const xAxis = g => g
            //     .attr("transform", `translate(0,${height - margin.bottom})`)
            //     .call(d3.axisTop(x).ticks(width / 80, data.format))
            //     // .call(g => g.select(".domain").remove())
            //     .call(g => g.selectAll("line").remove());

            svg.append("g")
                .attr("class", "grid")
                .attr('color', '#e8f1f8')
                .attr("transform", "translate(0," + (height - 20) + ")")
                .call(d3.axisBottom(x)
                    .ticks(3)
                    .tickSize(-height + 40)
                    .tickFormat(format)
                )

            svg.selectAll('text').style('font-size', 12).style('color', '#7d909f').style('transform', 'translate(0,5px)')

            ///////////////////////////////////////////////////////////////////////////////////////

            svg.append("g")
                .attr("fill", "#0c161e")
                .attr("stroke", "#0c161e")
                .attr("stroke-width", "0.3")
                .attr("text-anchor", "end")
                .attr("font-family", "NanumSquare")
                .attr("font-size", 15)
                .selectAll("text")
                .data(data)
                .join("text")
                .attr("x", d => x(0))
                .attr("y", (d, i) => y(i) + y.bandwidth() / 2 - barHeight / 2)
                .attr("dy", "1.1em")
                .attr("dx", -10)
                .text(d => d.name)
                // .call(text => text.filter(d => x(d.value) - x(0) < 20) // short bars
                //     .attr("dx", -10)
                //     .attr("fill", "black")
                //     .attr("text-anchor", "end"));

            svg.append("g")
                .attr("fill", "#0c161e")
                .attr("stroke", "#0c161e")
                .attr("stroke-width", "0.3")
                .attr("text-anchor", "end")
                .attr("font-family", "NanumSquare")
                .attr("font-size", 11)
                .selectAll("text")
                .data(data)
                .join("text")
                .attr("x", d => x(0))
                .attr("y", (d, i) => y(i) + y.bandwidth() / 2 - barHeight / 2)
                .attr("dy", "2.8em")
                .attr("dx", -10)
                .text(d => { if (d.hasOwnProperty('name2')) { return d.name2; } else return null; })
                // .call(text => text.filter(d => x(d.value) - x(0) < 20) // short bars
                //     .attr("dx", -80)
                //     .attr("fill", "black")
                //     .attr("text-anchor", "end"));

            svg.append("g")
                .attr("fill", '#cfdbe5')
                .selectAll("rect")
                .data(data)
                .join("rect")
                .attr("x", x(0))
                .attr("y", (d, i) => y(i) + y.bandwidth() / 2 - barHeight / 2)
                .attr("width", d => x(d.value) - x(0))
                .attr("height", barHeight)
                .classed("filled", (d, i) => { if (i < 1) return true; else return false;  });

            svg.append("g")
                // .attr("fill", "#616cec")
                // .attr("stroke", "#616cec")
                // .attr("stroke-width", "0.3")
                .attr("text-anchor", "start")
                .attr("font-family", "NanumSquare")
                .attr("font-size", 15)
                .selectAll("text")
                .data(data)
                .join("text")
                .attr("x", d => x(d.value))
                .attr("y", (d, i) => y(i) + y.bandwidth() / 2 - barHeight / 2)
                .attr("dy", "1.1em")
                .attr("dx", 5)
                .attr("stroke", (d, i) => { if (i < 1) return '#005ffc'; else return 'black'; })
                .attr("stroke-width", "0.3")
                .attr("fill", (d, i) => { if (i < 1) return '#005ffc'; else return 'black'; })
                .text(d => format(d.value))
                // .call(text => text.filter(d => x(d.value) - x(0) < 20) // short bars
                //     .attr("dx", +4)
                //     .attr("fill", "black")
                //     .attr("text-anchor", "start"));

            // svg.append("g")
            //     .call(xAxis);

            // svg.append("g")
            //     .call(yAxis);
        }

        const handleVerticalBarChart = (data, id) => {
            const margin = ({top: 15, right: 0, bottom: 40, left: 0});

            const height = 210;
            const width = 630;

            // const yAxis = g => g
            //     .attr("transform", `translate(${margin.left},0)`)
            //     .call(d3.axisLeft(y).ticks(null, data.format))
            //     .call(g => g.select(".domain").remove())
            //     .call(g => g.append("text")
            //         .attr("x", -margin.left)
            //         .attr("y", 10)
            //         .attr("fill", "currentColor")
            //         .attr("text-anchor", "start")
            //         .text(data.y));

            // const xAxis = g => g
            //     .attr("transform", `translate(0,${height - margin.bottom})`)
            //     .call(d3.axisBottom(x).tickFormat(i => data[i].name).tickSizeOuter(0));

            const y = d3.scaleLinear()
                .domain([0, 100]).nice()
                .range([height - margin.bottom, margin.top]);

            const x = d3.scaleBand()
                .domain(d3.range(data.length))
                .range([margin.left, width - margin.right])
                .padding(0.1);

            // const svg = d3.create("svg")
            //     .attr("viewBox", [0, 0, width, height]);

            const svg = d3.select(id/*'svg'*//*'#graph'*/).append('svg')
                .attr("viewBox", [0, 0, width, height]);

            const svgDefs = svg.append('defs');
            const mainGradient = svgDefs.append('linearGradient')
                .attr('id', 'barGradient')
                .attr('gradientTransform', 'rotate(90)');

            mainGradient.append('stop')
                .attr('class', 'stop-right2')
                .attr('offset', '0');

            mainGradient.append('stop')
                .attr('class', 'stop-left2')
                .attr('offset', '1');

            svg.append('line')
                .attr('x1', x(0))
                .attr('x2', x(data.length - 1) + x.bandwidth())
                .attr('y1', y(0))
                .attr('y2', y(0) + 0.5)
                .style('stroke', '#cfdbe5')
                .style('stroke-width', 1);

            svg.append("g")
                .attr("fill", '#cfdbe5')
                .selectAll("rect")
                .data(data)
                .join("rect")
                .attr("x", (d, i) => x(i) + (x.bandwidth() / 2) - 22.5)
                .attr("y", d => y(d.value))
                .attr("height", d => y(0) - y(d.value))
                .attr("width", 20)
                //.classed("filled-bar", true);
            // .attr("width", x.bandwidth());

            svg.append("g")
                //.attr("fill", '#cfdbe5')
                .selectAll("rect")
                .data(data)
                .join("rect")
                .attr("x", (d, i) => x(i) + (x.bandwidth() / 2) + 2.5)
                .attr("y", d => y(d.value2))
                .attr("height", d => y(0) - y(d.value2))
                .attr("width", 20)
                .classed("filled-bar", true);

            svg.append("g")
                .attr("fill", "#0c161e")
                .attr("stroke", "#0c161e")
                .attr("stroke-width", "0.1")
                .attr("text-anchor", "middle")
                .attr("font-family", "NanumSquare")
                .attr("font-size", 14)
                .selectAll("text")
                .data(data)
                .join("text")
                .attr("x", (d, i) => x(i) + (x.bandwidth() / 2))
                .attr("y", y(0) + 10)
                .attr("dy", 12.5)
                .attr("dx", 0)
                .text(d => d.name)
                // .call(text => text.filter(d => x(d.value) - x(0) < 20) // short bars
                //     .attr("dx", -80)
                //     .attr("fill", "black")
                //     .attr("text-anchor", "middle"));

            svg.append("g")
                .attr("fill", "#0c161e")
                .attr("stroke", "#0c161e")
                .attr("stroke-width", "0.1")
                .attr("text-anchor", "middle")
                .attr("font-family", "NanumSquare")
                .attr("font-size", 14)
                .selectAll("text")
                .data(data)
                .join("text")
                .attr("x", (d, i) => x(i) + (x.bandwidth() / 2))
                .attr("y", y(0))
                .attr("dy", 36)
                .attr("dx", 0)
                .text(d => { if (d.hasOwnProperty('name2')) { return d.name2; } else return null; })
                // .call(text => text.filter(d => x(d.value) - x(0) < 20) // short bars
                //     .attr("dx", -80)
                //     .attr("fill", "black")
                //     .attr("text-anchor", "middle"));

            svg.append("g")
                .attr("fill", "#0c161e")
                .attr("stroke", "#0c161e")
                .attr("stroke-width", "0.3")
                .attr("text-anchor", "middle")
                .attr("font-family", "NanumSquare")
                .attr("font-size", 13)
                .selectAll("text")
                .data(data)
                .join("text")
                .attr("x", (d, i) => x(i) + (x.bandwidth() / 2))
                .attr("y", (d, i) => y(d.value))
                .attr("dy", -5)
                .attr("dx", -12.5)
                .text(d => d.value)
                // .call(text => text.filter(d => x(d.value) - x(0) < 20) // short bars
                //     .attr("dx", -80)
                //     .attr("fill", "black")
                //     .attr("text-anchor", "middle"));

            svg.append("g")
                .attr("fill", "#616cec")
                .attr("stroke", "#616cec")
                .attr("stroke-width", "0.3")
                .attr("text-anchor", "middle")
                .attr("font-family", "NanumSquare")
                .attr("font-size", 13)
                .selectAll("text")
                .data(data)
                .join("text")
                .attr("x", (d, i) => x(i) + (x.bandwidth() / 2))
                .attr("y", (d, i) => y(d.value2))
                .attr("dy", -5)
                .attr("dx", 12.5)
                .text(d => d.value2)
                // .call(text => text.filter(d => x(d.value2) - x(0) < 20) // short bars
                //     .attr("dx", -80)
                //     .attr("fill", "black")
                //     .attr("text-anchor", "middle"));

            // svg.append("g")
            //     .call(xAxis);

            // svg.append("g")
            //     .call(yAxis);
        }

        window.onload = () => {
            const data = {!! json_encode($data) !!};

            const testData = [
                { name: '건국대', value: 4 },
                { name: '광운대', name2: '(최다)', value: 34 },
            ];
            handleHorizontalBarChart(data[0], '#lecture_count1');

            const testData2 = [
                { name: '건국대', value: 349 },
                { name: '한양대', name2: '(최다)', value: 13593 },
            ];
            handleHorizontalBarChart(data[1], '#lecture_count2');

            const testData3 = [
                { name: '수료율', value: 96, value2: 93 },
                { name: '최종성적', name2: '60점 이상 수료율', value: 96, value2: 93 },
                { name: '학습진도율', value: 62, value2: 50 },
                { name: '출석률', value: 96, value2: 80 },
                { name: '지각률', value: 5.8, value2: 6.0 },
                { name: '결석률', value: 0.3, value2: 0.4 },
            ];
            handleVerticalBarChart(data[6], '#att_sta');
        }
    </script>
@endsection

@section('content')
    <section class="content-wrap">
        <div class="content-top">
            <div class="content-top__left">
                <h3 class="tit">수강이수현황</h3>
            </div>
            <form class="w-100" method="GET" action="{{ route('dash2View') }}">
            <div class="content-top__right">
                <span class="fc-gray">학기정보</span>
                <select name="year" id="year">
                    @foreach(range(2010, 2050) as $y)
                        <option value="{{ $y }}" @if ($y == $year) selected @endif>{{ $y }}</option>
                    @endforeach
                </select>
                <span class="fc-gray">학년도</span>
                <select name="month" id="month">
                    @foreach([1,3,2,4] as $m)
                        <option value="{{ $m }}" @if ($m == $month) selected @endif>
                            @switch($m)
                                @case(1)
                                @case(2)
                                {{ $m }}
                                @break
                                @case(3)
                                여름계절
                                @break
                                @case(4)
                                겨울계절
                                @break
                            @endswitch
                        </option>
                    @endforeach
                </select>
                <span class="fc-gray"> 학기</span>
                <button class="btn btn-sm btn-primary">검색</button>
            </div>
            </form>
        </div>
        <div class="content">
            <div class="col-wrap">
                <div class="content-card col col-12">
                    <h4 class="content-card__tit">수강현황</h4>
                    <div class="col-wrap a-center">
                        <div class="col col-5 p-20">
                            <div id="lecture_count1"></div>
                            <p class="fs-md t-center p-t-20">총 {{ $data[2] }}과목 중 <strong class="fc-blue">{{ $data[0][0]['value'] }}개</strong>과목 참여</p>
                        </div>
                        <div class="col col-5 p-20">
                            <div id="lecture_count2"></div>
{{--                            <p class="fs-md t-center p-t-20">총 {{ $data[3] }}명 중 <strong class="fc-blue">{{ number_format($data[1][0]['value']) }}명</strong>수강</p>--}}
                            <p class="fs-md t-center p-t-20">총 <strong class="fc-blue">{{ number_format($data[1][0]['value']) }}명</strong>수강</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-wrap">
                <div class="content-card col col-5 h-360">
                    <span class="label label-blue">주요사항</span>
                    <ul class="dot-list">
                        <li class="dot-list__item blue">
                            {{ $year }}학년도 {{ $month }}학기 개설 과목 {{ $data[2] }}개 과목 중 {{ $data[0][0]['value'] }}개 과목에 참여하였으며 총 {{ number_format($data[1][0]['value']) }}명의 학생이 수강하였음
                        </li>
                        @if($data[4] != '' && $data[5] != '')
                        <li class="dot-list__item blue">
                            가장 많은 인원이 수강한 과목은 [{{ $data[4] }}]으로 {{ $data[5] }}명의 학생이 수강함
                        </li>
                        @endif
                    </ul>
                </div>
                <div class="content-card col col-7 h-360">
                    <table class="table t-center">
                        <colgroup>
                            <col width="50%" />
                            <col width="15%" />
                            <col width="20%" />
                            <col width="15%" />
                        </colgroup>
                        <tr>
                            <th>참여과목 명</th>
                            <th>교수명</th>
                            <th>개발대학</th>
                            <th>수강인원</th>
                        </tr>
                        @if ($lectures)
                            @foreach($lectures->sortByDesc('memberCount') as $lecture)
                            <tr>
                                <td>{{ $lecture->lectureName }}</td>
                                <td>{{ $lecture->professorName }}</td>
                                <td>{{ $lecture->professorUnivName }}</td>
                                <td>{{ number_format($lecture->memberCount) }}</td>
                            </tr>
                            @endforeach
                            <tr>
                                <td colspan="3" class="bg-blue">합계</td>
                                <td class="bg-blue">{{ number_format($lectures->sum('memberCount')) }}</td>
                            </tr>
                        @endif
                    </table>
                </div>
            </div>
            <div class="col-wrap">
                <div class="content-card col col-12">
                    <h4 class="content-card__tit">이수현황</h4>
                    <div class="p-t-30">
                        <div class="graph-div col col-9 p-t-30 m-r-20">
                            <div class="graph-div__left">
                                <span class="circle-sm circle-gray"></span>참여대학평균<br>
                                <span class="circle-sm circle-purple"></span>{{ auth()->user()->univName }}평균<br>
                                (단위 : %)
                            </div>
                            <div class="graph-div2" id="att_sta"></div>
                        </div>
                        <div class="graph-div col col-3 a-center">
                            <div class="m-r-10">
                                <div class="circle-lg circle-pink">수료율<br><strong>{{ $data[6][0]['value2'] }}%</strong></div>
                                <div class="circle-lg circle-lightpurple l-h-1">최종성적<br>60점이상 취득률<br><strong>{{ $data[6][1]['value2'] }}%</strong></div>
                            </div>
                            <div>
                                <div class="circle-lg circle-pink">총 {{ number_format($data[1][0]['value']) }}명 중<br><strong>{{ number_format($data[7]) }}명</strong>수료</div>
                                <div class="circle-lg circle-lightpurple">총 {{ number_format($data[1][0]['value']) }}명 중<br><strong>{{ number_format($data[7]) }}명</strong>수료</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-wrap">
                <div class="content-card col col-5 h-360">
                    <span class="label label-purple">주요사항</span>
                    <ul class="dot-list">
                        <li class="dot-list__item purple">
                            {{ $year }}학년도 {{ $month }}학기 수강생 {{ number_format($data[1][0]['value']) }}명 중 {{ number_format($data[7]) }}명이 수료, {{ $data[6][0]['value2'] }}%의 수료율을 나타냄
                        </li>
                        <li class="dot-list__item purple">
                            수료율은 평균{{ $data[6][0]['value2'] > $data[6][0]['value'] ? '보다 높게' : ($data[6][0]['value2'] == $data[6][0]['value'] ? '과 같게' : '보다 낮게') }} 나타나며, 학습진도율은 평균{{ $data[6][2]['value2'] > $data[6][2]['value'] ? '보다 높게' : ($data[6][2]['value2'] == $data[6][2]['value'] ? '과 같게' : '보다 낮게') }} 나타남
                        </li>
                        <li class="dot-list__item purple">
                            최종성적이 60점 이상인 실제 취득자는 {{ number_format($data[8]['overSixty']) }}명으로, {{ $data[8]['overSixtyRate'] }}%의 취득률이 나타남
                        </li>
                        <li class="dot-list__item purple">
                            출석률이 평균{{ $data[6][3]['value2'] > $data[6][3]['value'] ? '보다 높게' : ($data[6][3]['value2'] == $data[6][3]['value'] ? '과 같게' : '보다 낮게') }} 나타나며, 지각률은 평균{{ $data[6][4]['value2'] > $data[6][4]['value'] ? '보다 높게' : ($data[6][4]['value2'] == $data[6][4]['value'] ? '과 같게' : '보다 낮게') }} 나타나고, 결석률은 평균{{ $data[6][5]['value2'] > $data[6][5]['value'] ? '보다 높게' : ($data[6][5]['value2'] == $data[6][5]['value'] ? '과 같게' : '보다 낮게') }} 나타남
                        </li>
                        @if($data[9]['lectureName'] != '' && $data[9]['absenceRate'] != '')
                        <li class="dot-list__item purple">
                            [{{ $data[9]['lectureName'] }}] 과목이 결석률 {{ $data[9]['absenceRate'] }}%로 참여도가 가장 높음
                        </li>
                        @endif
                    </ul>
                </div>
                <div class="content-card col col-7 h-360">
                    <table class="table t-center">
                        <colgroup>
                            <col width="28%" />
                            <col width="12%" />
                            <col width="12%" />
                            <col width="12%" />
                            <col width="12%" />
                            <col width="12%" />
                            <col width="12%" />
                        </colgroup>
                        <tr>
                            <th>참여과목 명</th>
                            <th>수료인원</th>
                            <th>수료율</th>
                            <th>학습진도율</th>
                            <th>출석율</th>
                            <th>지각률</th>
                            <th>결석률</th>
                        </tr>
                        @if ($lectures)
                            @foreach($lectures->sortByDesc('graduatesCount') as $lecture)
                            <tr>
                                <td>{{ $lecture->lectureName }}</td>
                                <td>{{ number_format($lecture->graduatesCount) }}</td>
                                <td>@if($lecture->finalCompletionRate == 0) - @else{{ $lecture->finalCompletionRate }}% @endif</td>
                                <td>@if($lecture->learningProgressRate == 0) - @else{{ $lecture->learningProgressRate }}%@endif</td>
                                <td>@if($lecture->attendanceRate == 0) - @else{{ $lecture->attendanceRate }}%@endif</td>
                                <td>@if($lecture->lateRate == 0) - @else{{ $lecture->lateRate }}%@endif</td>
                                <td>@if($lecture->absenceRate == 0) - @else{{ $lecture->absenceRate }}%@endif</td>
                            </tr>
                            @endforeach
                            <tr>
                                <td colspan="2" class="bg-purple">평균</td>
                                <td class="bg-purple">@if($lectures->avg('finalCompletionRate') == 0) - @else{{ floor($lectures->avg('finalCompletionRate') * 100) / 100 }}%@endif</td>
                                <td class="bg-purple">@if($lectures->avg('learningProgressRate') == 0) - @else{{ floor($lectures->avg('learningProgressRate') * 100) / 100 }}%@endif</td>
                                <td class="bg-purple">@if($lectures->avg('attendanceRate') == 0) - @else{{ floor($lectures->avg('attendanceRate') * 100) / 100 }}%@endif</td>
                                <td class="bg-purple">@if($lectures->avg('lateRate') == 0) - @else{{ floor($lectures->avg('lateRate') * 100) / 100 }}%@endif</td>
                                <td class="bg-purple">@if($lectures->avg('absenceRate') == 0) - @else{{ floor($lectures->avg('absenceRate') * 100) / 100 }}%@endif</td>
                            </tr>
                        @endif
                    </table>
                </div>
            </div>
        </div>
    </section>
    {{--<div class="container">--}}
    {{--    <div class="row justify-content-center">--}}
    {{--        <div class="col-md-8">--}}
    {{--            <div class="card">--}}
    {{--                <div class="card-header">{{ __('Dashboard') }}</div>--}}

    {{--                <div class="card-body">--}}
    {{--                    @if (session('status'))--}}
    {{--                        <div class="alert alert-success" role="alert">--}}
    {{--                            {{ session('status') }}--}}
    {{--                        </div>--}}
    {{--                    @endif--}}

    {{--                    {{ __('You are logged in!') }}--}}
    {{--                </div>--}}
    {{--            </div>--}}
    {{--        </div>--}}
    {{--    </div>--}}
    {{--</div>--}}
@endsection
