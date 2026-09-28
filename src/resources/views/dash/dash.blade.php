@extends('layouts.app')

@section('script')
    <script src="{{ asset('js/d3.js') }}"></script>

    <script>
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

            svg.append('text').style('font-size', '18px').style('fill', '#0c161e').style('font-weight','bold')
                .attr('text-anchor', 'middle').attr('transform', 'translate(0, 5)')
                .text(data[0].value.toFixed(2) + '%');
        }

        function handleHorizontalBarChart(data, id) {
            const margin = ({top: 0, right: 45, bottom: 0, left: 110})
            const barHeight = 25;
            const width = 430;
            // const height = Math.ceil((data.length + .4) * barHeight) + margin.top + margin.bottom;
            const height = 200;

            // const yAxis = g => g
            //     .attr("transform", `translate(${margin.left},0)`)
            //     .call(d3.axisLeft(y).tickFormat(i => data[i].name).tickSizeOuter(0));

            // const xAxis = g => g
            //     .attr("transform", `translate(0,${margin.top})`)
            //     .call(d3.axisTop(x).ticks(width / 80, data.format))
            //     .call(g => g.select(".domain").remove());

            const y = d3.scaleBand()
                .domain(d3.range(data.length))
                .rangeRound([margin.top, height - margin.bottom])
                .padding(0.1);

            const x = d3.scaleLinear()
                .domain([0, 1])
                .range([margin.left, width - margin.right]);

            const format = x.tickFormat(20, '%');

            const svg = d3.select(id/*'svg'*//*'#graph'*/).append('svg')
                .attr("viewBox", [0, 0, width, height]);

            const svgDefs = svg.append('defs');
            const mainGradient = svgDefs.append('linearGradient')
                .attr('id', 'barGradient');
            // .attr('gradientTransform', 'rotate(45)');

            mainGradient.append('stop')
                .attr('class', 'stop-left2')
                .attr('offset', '0');

            mainGradient.append('stop')
                .attr('class', 'stop-right2')
                .attr('offset', '1');

            svg.append("g")
                .attr("fill", "#0c161e")
                .attr("stroke", "#0c161e")
                .attr("stroke-width", "0.3")
                .attr("text-anchor", "start")
                .attr("font-family", "NanumSquare")
                // .attr("font-size", 15)
                .selectAll("text")
                .data(data)
                .join("text")
                .attr("x", d => 0)
                .attr("y", (d, i) => { return i * 30 + i * 25 })
                .attr("dy", (d) => { if (d.hasOwnProperty('name2')) { return ".75em"; } else return "1.1em"; })
                .attr("dx", 0)
                .attr("font-size", (d) => { if (d.hasOwnProperty('name2')) { return 14; } else return 15; })
                .text(d => d.name)
                .call(text => text.filter(d => x(d.value) - x(0) < 20) // short bars
                    .attr("dx", 0)
                    .attr("fill", "black")
                    .attr("text-anchor", "start"));

            svg.append("g")
                .attr("fill", "#0c161e")
                .attr("stroke", "#0c161e")
                .attr("stroke-width", "0.3")
                .attr("text-anchor", "start")
                .attr("font-family", "NanumSquare")
                .attr("font-size", 14)
                .selectAll("text")
                .data(data)
                .join("text")
                .attr("x", d => 0)
                .attr("y", (d, i) => { return i * 30 + i * 25 })
                .attr("dy", "1.9em")
                .attr("dx", 0)
                .text(d => { if (d.hasOwnProperty('name2')) { return d.name2; } else return null; })
                // .call(text => text.filter(d => x(d.value) - x(0) < 20) // short bars
                //     .attr("dx", 0)
                //     .attr("fill", "black")
                //     .attr("text-anchor", "start"));

            svg.append("g")
                .attr("fill", "#e8f1f8")
                .selectAll("rect")
                .data(data)
                .join("rect")
                .attr("x", x(0))
                .attr("y", (d, i) => { return i * 30 + i * 25 })
                .attr("width", d => x(1) - x(0))
                .attr("height", barHeight);

            svg.append("g")
                .attr("fill", "transparent")
                .selectAll("rect")
                .data(data)
                .join("rect")
                .attr("x", x(0))
                .attr("y", (d, i) => { return i * 30 + i * 25 })
                .attr("width", d => x(d.value) - x(0))
                .attr("height", barHeight)
                .classed("filled-bar", true);

            svg.append("g")
                .attr("fill", "#616cec")
                .attr("stroke", "#616cec")
                .attr("stroke-width", "0.3")
                .attr("text-anchor", "end")
                .attr("font-family", "NanumSquare")
                .attr("font-size", 15)
                .selectAll("text")
                .data(data)
                .join("text")
                .attr("x", d => x(1))
                .attr("y", (d, i) => { return i * 30 + i * 25 })
                .attr("dy", "1.1em")
                .attr("dx", 45)
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

        const handleVerticalBarChart = (data, id, graphNum, graphId) => {
            const margin = ({top: 15, right: 0, bottom: 30, left: 0});

            const height = 240;
            const width = 480;

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
                // .domain([0, d3.max(data, d => d.value > d.value2 ? d.value : d.value2)]).nice()
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
                .attr('id', graphId)
                .attr('gradientTransform', 'rotate(90)');

            mainGradient.append('stop')
                .attr('class', `stop-right${graphNum}`)
                .attr('offset', '0');

            mainGradient.append('stop')
                .attr('class', `stop-left${graphNum}`)
                .attr('offset', '1');

            svg.append("g")
                .attr("fill", '#cfdbe5')
                .selectAll("rect")
                .data(data)
                .join("rect")
                .attr("x", (d, i) => x(i) + (x.bandwidth() / 2) - 19.5)
                .attr("y", d => y(d.value))
                .attr("height", d => y(0) - y(d.value))
                .attr("width", 16)
                // .classed(`filled${Number(graphNum) - 1}`, true);
                // .attr("width", x.bandwidth());

            svg.append("g")
                //.attr("fill", '#f00')
                .selectAll("rect")
                .data(data)
                .join("rect")
                .attr("x", (d, i) => x(i) + (x.bandwidth() / 2) + 3.5)
                .attr("y", d => y(d.value2))
                .attr("height", d => y(0) - y(d.value2))
                .attr("width", 16)
                .classed(`filled${Number(graphNum) - 1}`, true);

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
                .attr("y", y(0))
                .attr("dy", 14)
                .attr("dx", 0)
                .text(d => d.name)
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
                .attr("y", y(0))
                .attr("dy", 28)
                .attr("dx", 0)
                .text(d => { if (d.hasOwnProperty('name2')) { return d.name2; } else return null; })
                // .call(text => text.filter(d => x(d.value) - x(0) < 20) // short bars
                //     .attr("dx", -80)
                //     .attr("fill", "black")
                //     .attr("text-anchor", "middle"));

            svg.append("g")
                .attr("fill", "#0c161e")
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
                .attr("dx", -12)
                .text(d => d.value)
                // .call(text => text.filter(d => x(d.value) - x(0) < 20) // short bars
                //     .attr("dx", -80)
                //     .attr("fill", "black")
                //     .attr("text-anchor", "middle"));

            svg.append("g")
                .attr("fill", graphNum == 3 ? "#ff2a66" : "#00b9a1")
                .attr("stroke", graphNum == 3 ? "#ff2a66" : "#00b9a1")
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
                .attr("dx", 11)
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

            if (Array.isArray(data[1]) && data[1].length > 0) {
                handleDonutChart(data[1], '#lecture_count1');
            }

            if (Array.isArray(data[3]) && data[3].length > 0) {
                handleDonutChart(data[3], '#lecture_count2');
            }

            handleHorizontalBarChart(data[5], '#att_rate');

            handleVerticalBarChart(data[6], '#att_stt', '3', 'mainGradient2');

            handleVerticalBarChart(data[7], '#att_sat', '4', 'mainGradient3');
        }
    </script>
@endsection

@section('content')
    <section class="content-wrap">
        <div class="content-top">
            <div class="content-top__left">
                <h3 class="tit">종합운영결과</h3>
            </div>
            <form class="w-100" method="GET" action="{{ route('dashView') }}">
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
                <div class="content-card col col-6">
                    <h4 class="content-card__tit"><a href="{{ route('dash2View') }}">수강현황</a></h4>
                    <div class="graph-wrap">
                        <div class="graph donut" id="lecture_count1">
                            <h5 class="graph-tit__type1">수강과목수</h5>
                            <span class="graph-count"><span class="fc-blue">{{ $data[0]['lectureCount'] }}개</span>/{{ $data[0]['totalLectureCount'] }}개</span>
                        </div>
{{--                        <div class="graph donut" id="lecture_count2">--}}
{{--                            <h5 class="graph-tit__type1">수강인원수</h5>--}}
{{--                            <span class="graph-count"><span class="fc-blue">{{ $data[2]['memberCount'] }}명</span>/{{ $data[2]['totalMemberCount'] }}명</span>--}}
{{--                        </div>--}}
                        <div class="graph donut">
                            <h5 class="graph-tit__type1">수강인원수</h5>
                            <div class="graph-round__type1">
                                {{ number_format($data[2]['memberCount']) }}명
                            </div>
                            <span class="graph-count"><span class="fc-blue">{{ auth()->user()->univName }}</span></span>
                        </div>
                        <div class="graph donut">
                            <h5 class="graph-tit__type1">최다수강과목</h5>
                            <div class="graph-round__type1">
                                {{ number_format($data[4]['value']) }}명
                            </div>
                            <span class="graph-count"><span class="fc-blue">{{ $data[4]['name'] }}</span></span>
                        </div>
                    </div>
                </div>
                <div class="content-card col col-6">
                    <h4 class="content-card__tit"><a href="{{ route('dash2View') }}">이수현황</a></h4>
                    <div class="graph-wrap">
                        <div class="graph bar-100" id="att_rate">

                        </div>
                    </div>
                </div>
            </div>
            <div class="col-wrap">
                <div class="content-card col col-6">
                    <h4 class="content-card__tit"><a href="{{ route('dash3View') }}">참여현황</a></h4>
                    <div class="graph-div">
                        <div class="graph-div__left">
                            <span class="circle-sm circle-gray"></span>참여대학평균<br>
                            <span class="circle-sm circle-red"></span>{{ auth()->user()->univName }}평균
                        </div>
                        <div class="graph-div__right">
                            (단위 : %)
                        </div>
                    </div>
                    <div id="att_stt"></div>
                </div>
                <div class="content-card col col-6">
                    <h4 class="content-card__tit"><a href="{{ route('sat1View') }}">만족도결과</a></h4>
                    <div class="graph-div">
                        <div class="graph-div__left">
                            <span class="circle-sm circle-gray"></span>참여대학평균<br>
                            <span class="circle-sm circle-green"></span>{{ auth()->user()->univName }}평균
                        </div>
                        <div class="graph-div__right">
                            (단위 : %)
                        </div>
                    </div>
                    <div id="att_sat"></div>
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
