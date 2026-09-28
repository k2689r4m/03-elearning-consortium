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
            const width = 720;
            // const height = Math.ceil((data.length + .4) * barHeight) + margin.top + margin.bottom;
            const height = 180;

            // const yAxis = g => g
            //     .attr("transform", `translate(${margin.left},0)`)
            //     .call(d3.axisLeft(y).tickFormat(i => data[i].name).tickSizeOuter(0));

            const x = d3.scaleLinear()
                .domain([0, 100])
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
                .attr('class', 'stop-left3')
                .attr('offset', '0');

            mainGradient.append('stop')
                .attr('class', 'stop-right3')
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
                    .ticks(10)
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
                .attr("stroke", (d, i) => { if (i < 1) return '#ff2a66'; else return 'black'; })
                .attr("stroke-width", "0.3")
                .attr("fill", (d, i) => { if (i < 1) return '#ff2a66'; else return 'black'; })
                .text(d => format(d.value))

            // svg.append("g")
            //     .call(xAxis);

            // svg.append("g")
            //     .call(yAxis);
        }

        const handleVerticalBarChart = (data, id) => {
            const margin = ({top: 15, right: 0, bottom: 30, left: 0});

            const height = 200;
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
                // .domain([0, d3.max(data, d => d.value)]).nice()
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
                .attr('class', 'stop-right3')
                .attr('offset', '0');

            mainGradient.append('stop')
                .attr('class', 'stop-left3')
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
                .attr("x", (d, i) => x(i) + (x.bandwidth() / 2) - 25)
                .attr("y", d => y(d.value))
                .attr("height", d => y(0) - y(d.value))
                .attr("width", 25)
                //.classed("filled-bar", true);
            // .attr("width", x.bandwidth());

            svg.append("g")
                .attr("fill", '#cfdbe5')
                .selectAll("rect")
                .data(data)
                .join("rect")
                .attr("x", (d, i) => x(i) + (x.bandwidth() / 2) + 5)
                .attr("y", d => y(d.value2))
                .attr("height", d => y(0) - y(d.value2))
                .attr("width", 25)
                .classed("filled-bar", true);

            svg.append("g")
                .attr("fill", "#0c161e")
                .attr("stroke", "#0c161e")
                .attr("stroke-width", "0.1")
                .attr("text-anchor", "middle")
                .attr("font-family", "NanumSquare")
                .attr("font-size", 15)
                .selectAll("text")
                .data(data)
                .join("text")
                .attr("x", (d, i) => x(i) + (x.bandwidth() / 2))
                .attr("y", y(0) + 10)
                .attr("dy", 12.5)
                .attr("dx", 0)
                .text(d => d.name)
                .call(text => text.filter(d => x(d.value) - x(0) < 20) // short bars
                    .attr("dx", 5)
                    .attr("fill", "black")
                    .attr("text-anchor", "middle"));

            // svg.append("g")
            //     .attr("fill", "#0c161e")
            //     .attr("stroke", "#0c161e")
            //     .attr("stroke-width", "0.3")
            //     .attr("text-anchor", "middle")
            //     .attr("font-family", "NanumSquare")
            //     .attr("font-size", 12)
            //     .selectAll("text")
            //     .data(data)
            //     .join("text")
            //     .attr("x", (d, i) => x(i) + (x.bandwidth() / 2))
            //     .attr("y", y(0))
            //     .attr("dy", 25)
            //     .attr("dx", 0)
            //     .text(d => { if (d.hasOwnProperty('name2')) { return d.name2; } else return null; })
            //     .call(text => text.filter(d => x(d.value) - x(0) < 20) // short bars
            //         .attr("dx", -80)
            //         .attr("fill", "black")
            //         .attr("text-anchor", "middle"));

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
                .call(text => text.filter(d => x(d.value) - x(0) < 20) // short bars
                    .attr("dx", -20)
                    .attr("fill", "black")
                    .attr("text-anchor", "middle"));

            svg.append("g")
                .attr("fill", "#ff2a66")
                .attr("stroke", "#ff2a66")
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
                .attr("dx", 17.5)
                .text(d => d.value2)
                .call(text => text.filter(d => x(d.value2) - x(0) < 20) // short bars
                    .attr("dx", 20)
                    .attr("fill", "black")
                    .attr("text-anchor", "middle"));

            // svg.append("g")
            //     .call(xAxis);

            // svg.append("g")
            //     .call(yAxis);
        }

        function handleDonutChart (data, id)  {

            /* test code */
            // data[0] = {name: 'true', value: 25};
            // data[1] = {name: 'false', value: 75};

            const trueColor = 'transparent';
            const falseColor = '#e8f1f8';

            let height = 115
            let width = 115

            const radius = Math.min(width, height) / 2;

            const arc = d3.arc()
                .innerRadius(radius - 15)
                .outerRadius(radius);

            const pie = d3.pie()
                .sort((a, b) => 0)
                .value(function(d) { return d.value; });

            const arcs = pie(data);

            const svg = d3.select(id/*'svg'*//*'#graph'*/).append('svg').attr('viewBox', [-width / 2, -height / 2, width, height])
                .attr('text-anchor', 'middle')
                .style('font-size', '12px sans-serif');

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

            svg.append('g').selectAll('path')
                .data(arcs)
                .enter().append('path')
                .classed('filled', function (d, i) { if (d.name === 'true') return true; else return false; } )
                .attr('fill', function(d, i) { return d.data.name === 'true' ? "url(#mainGradient)" : falseColor })
                .attr('d', arc)

            svg.append('text').style('font-size', '18px').style('fill', '#ff2a66').style('font-weight','bold')
                .attr('text-anchor', 'middle').attr('transform', 'translate(0, 5)')
                .text(data[0].value.toFixed(2) + '%');
        }

        window.onload = () => {
            const data = {!! json_encode($data) !!};

            handleHorizontalBarChart(data[0], '#par1');

            handleDonutChart(data[1], '#par2');

            handleVerticalBarChart(data[5], '#comp_sta');
        }
    </script>
@endsection

@section('content')
    <section class="content-wrap">
        <div class="content-top">
            <div class="content-top__left">
                <h3 class="tit">참여현황</h3>
            </div>
            <form class="w-100" method="GET" action="{{ route('dash3View') }}">
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
                    <h4 class="content-card__tit">참여현황</h4>
                    <div class="col-wrap a-center">
                        <div class="col col-8 p-20">
                            <div id="par1"></div>
                            <p class="fs-md t-center p-t-20">시험응시율 <strong class="fc-red">{{ $data[0][0]['value'] }}%</strong></p>
                        </div>
                        <div class="col col-2 p-20 p-t-0">
                            <div class="graph donut" id="par2">
                                <div class="graph-count row-1">
                                    <span class="w-130">시험응시율 상위과목</span>
                                    <div class="line"></div>
                                    <span class="fc-red">{{ $data[2] }}</span>
                                </div>
                            </div>
{{--                            <div id="lecture_count2"></div>--}}
{{--                            <p class="fs-md t-center p-t-20">총 13,593명 중 <strong class="fc-blue">349명</strong>수강</p>--}}
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-wrap">
                <div class="content-card col col-5 h-360">
                    <span class="label label-red">주요사항</span>
                    <ul class="dot-list">
                        <li class="dot-list__item red">
                            수강생 {{ number_format($data[3]['totalStu']) }}명 중 중간시험 응시생 수 {{ number_format($data[3]['midTest']) }}명, 기말시험 응시생 수 {{ number_format($data[3]['finalTest']) }}명으로 평균 {{ $data[3]['avg'] }}% 응시율을 나타냄
                        </li>
                        <li class="dot-list__item red">
                            가장 많은 인원이 수강한 과목은 [{{ $data[4]['lectureName'] }}](으)로 {{ number_format($data[4]['stuTotal']) }}명의 학생이 수강함
                        </li>
                    </ul>
                </div>
                <div class="content-card col col-7 h-360">
                    <table class="table t-center th-col-2">
                        <colgroup>
                            <col width="40%" />
                            <col width="12%" />
                            <col width="12%" />
                            <col width="12%" />
                            <col width="12%" />
                            <col width="12%" />
                        </colgroup>
                        <tr>
                            <th rowspan="2">참여과목 명</th>
                            <th colspan="2">중간고사</th>
                            <th colspan="2">기말고사</th>
                            <th rowspan="2">총 응시율</th>
                        </tr>
                        <tr>
                            <th>학생수</th>
                            <th>응시율</th>
                            <th>학생수</th>
                            <th>응시율</th>
                        </tr>
                        @if ($lectures)
                            @foreach($lectures->sortByDesc('testApplicationRate') as $lecture)
                                <tr>
                                    <td>{{ $lecture->lectureName }}</td>
                                    <td>{{ number_format($lecture->memberCount) }}</td>
                                    <td>@if($lecture->midtermTestApplicationRate == 0) - @else{{ $lecture->midtermTestApplicationRate }}%@endif</td>
                                    <td>{{ number_format($lecture->memberCount) }}</td>
                                    <td>@if($lecture->finalTestApplicationRate == 0) - @else{{ $lecture->finalTestApplicationRate }}%@endif</td>
                                    <td>@if($lecture->testApplicationRate == 0) - @else{{ $lecture->testApplicationRate }}%@endif</td>
                                </tr>
                            @endforeach
                            <tr>
                                <td class="bg-red">평균</td>
                                <td class="bg-red">&nbsp;</td>
                                <td class="bg-red">@if($lectures->avg('midtermTestApplicationRate') == 0) - @else{{ floor($lectures->avg('midtermTestApplicationRate') * 100) / 100 }}%@endif</td>
                                <td class="bg-red">&nbsp;</td>
                                <td class="bg-red">@if($lectures->avg('finalTestApplicationRate') == 0) - @else{{ floor($lectures->avg('finalTestApplicationRate') * 100) / 100 }}%@endif</td>
                                <td class="bg-red">@if($lectures->avg('testApplicationRate') == 0) - @else{{ floor($lectures->avg('testApplicationRate') * 100) / 100 }}%@endif</td>
                            </tr>
                        @endif
                    </table>
                </div>
            </div>
            <div class="col-wrap">
                <div class="content-card col col-12">
                    <h4 class="content-card__tit">이수현황</h4>
                    <div class="p-t-30">
                        <div class="graph-div col col-2">
                            <div class="graph-div__left">
                                <span class="circle-sm circle-gray"></span>참여대학평균<br>
                                <span class="circle-sm circle-red"></span>{{ auth()->user()->univName }}<br>
                                (단위 : %)
                            </div>
                        </div>
                        <div class="col col-8 p-t-30 m-l-50" id="comp_sta">
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-wrap">
                <div class="content-card col col-5 h-360">
                    <span class="label label-red">주요사항</span>
                    <ul class="dot-list">
                        @if ($lectures && $firstTotal)
                        <li class="dot-list__item red">
                            과제수행률이 {{ $lectures->avg('taskPerformanceRate') === $firstTotal->taskPerformanceRate ? '평균과 같' : ($lectures->avg('taskPerformanceRate') > $firstTotal->taskPerformanceRate ? '평균보다 높' : '평균보다 낮') }}게 나타남
                        </li>
                        <li class="dot-list__item red">
                            토론참여율이 {{ $lectures->avg('discussionParticipationRate') === $firstTotal->discussionParticipationRate ? '평균과 같' : ($lectures->avg('discussionParticipationRate') > $firstTotal->discussionParticipationRate ? '평균보다 높' : '평균보다 낮') }}게 나타남
                        </li>
                        <li class="dot-list__item red">
                            퀴즈수행률이 {{ $lectures->avg('quizProgressRate') === $firstTotal->quizProgressRate ? '평균과 같' : ($lectures->avg('quizProgressRate') > $firstTotal->quizProgressRate ? '평균보다 높' : '평균보다 낮') }}게 나타남
                        </li>
                        <li class="dot-list__item red">
                            학습참여도가 {{ $lectures->avg('learningParticipationRate') === $firstTotal->learningParticipationRate ? '평균과 같' : ($lectures->avg('learningParticipationRate') > $firstTotal->learningParticipationRate ? '평균보다 높' : '평균보다 낮') }}게 나타남
                        </li>
                        @endif
                    </ul>
                </div>
                <div class="content-card col col-7 h-360">
                    <table class="table t-center">
                        <colgroup>
                            <col width="52%" />
                            <col width="12%" />
                            <col width="12%" />
                            <col width="12%" />
                            <col width="12%" />
                        </colgroup>
                        <tr>
                            <th>참여과목 명</th>
                            <th>과제수행률</th>
                            <th>토론참여율</th>
                            <th>퀴즈수행률</th>
                            <th>학습참여도</th>
                        </tr>
                        @if ($lectures)
                            @foreach($lectures->sortByDesc('taskPerformanceRate') as $lecture)
                            <tr>
                                <td>{{ $lecture->lectureName }}</td>
                                <td>@if($lecture->taskPerformanceRate == 0) - @else{{ $lecture->taskPerformanceRate }}%@endif</td>
                                <td>@if($lecture->discussionParticipationRate == 0) - @else{{ $lecture->discussionParticipationRate }}%@endif</td>
                                <td>@if($lecture->quizProgressRate == 0) - @else{{ $lecture->quizProgressRate }}%@endif</td>
                                <td>@if($lecture->learningParticipationRate == 0) - @else{{ $lecture->learningParticipationRate }}%@endif</td>
                            </tr>
                            @endforeach
                            <tr>
                                <td class="bg-red">평균</td>
                                <td class="bg-red">@if($lectures->avg('taskPerformanceRate') == 0) - @else{{ floor($lectures->avg('taskPerformanceRate') * 100) / 100 }}%@endif</td>
                                <td class="bg-red">@if($lectures->avg('discussionParticipationRate') == 0) - @else{{ floor($lectures->avg('discussionParticipationRate') * 100) / 100 }}%@endif</td>
                                <td class="bg-red">@if($lectures->avg('quizProgressRate') == 0) - @else{{ floor($lectures->avg('quizProgressRate') * 100) / 100 }}%@endif</td>
                                <td class="bg-red">@if($lectures->avg('learningParticipationRate') == 0) - @else{{ floor($lectures->avg('learningParticipationRate') * 100) / 100 }}%@endif</td>
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
