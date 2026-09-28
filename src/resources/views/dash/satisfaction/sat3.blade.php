@extends('layouts.sat')

@section('script')
    <script src="{{ asset('js/d3.js') }}"></script>

    <script>
        function handleHorizontalBarChart(data, id, colorType) {
            const names = [data[0].name, data[1].name];
            let maxLen = 0;
            names.forEach((name) => {
                const nameArray = [...name];
                let len = 0;
                nameArray.forEach((char, key, array) => {
                    if (char >= 'a' && char <= 'z') {
                        len += 7;

                        if (key == 0) {
                            len += 11;
                        }
                        else if (key == array.length - 1) {
                            len += 11;
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

            let min = d3.min(data, d => d.value);
            min = (Math.floor(min / 10)) * 10;

            if (min > 5) {
                min -= 5;
            }

            let max = d3.max(data, d => d.value).toString();

            const margin = ({top: 35, right: (max.toString().length < 1 ? 1 : max.toString().length) * 11, bottom: 35, left: maxLen})
            const barHeight = 24;
            const width = 455;
            // const height = Math.ceil((data.length + .4) * barHeight) + margin.top + margin.bottom;
            const height = 160;

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

            if(colorType === 'blue'){
                const mainGradient2 = svgDefs.append('linearGradient')
                    .attr('id', 'mainGradient2');
                mainGradient2.append('stop')
                    .attr('class', 'stop-right')
                    .attr('offset', '0');
                mainGradient2.append('stop')
                    .attr('class', 'stop-left')
                    .attr('offset', '1');
            }else if(colorType === 'red'){
                const mainGradient3 = svgDefs.append('linearGradient')
                    .attr('id', 'mainGradient3');
                mainGradient3.append('stop')
                    .attr('class', 'stop-left3')
                    .attr('offset', '0');
                mainGradient3.append('stop')
                    .attr('class', 'stop-right3')
                    .attr('offset', '1');
            }else{
                const mainGradient = svgDefs.append('linearGradient')
                    .attr('id', 'mainGradient');
                mainGradient.append('stop')
                    .attr('class', 'stop-left4')
                    .attr('offset', '0');
                mainGradient.append('stop')
                    .attr('class', 'stop-right4')
                    .attr('offset', '1');
            }

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
                    .ticks(4)
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
                .attr("y", (d, i) => y(i) + i * 10 - 5)
                .attr("dy", d => { if (d.hasOwnProperty('name2')) { return ".75em"; } else return "1.1em"; })
                .attr("dx", -10)
                .text(d => d.name)

            svg.append("g")
                .attr("fill", '#cfdbe5')
                .selectAll("rect")
                .data(data)
                .join("rect")
                .attr("x", x(0))
                .attr("y", (d, i) => y(i) + i * 10 - 4)
                .attr("width", d => x(d.value) - x(0))
                .attr("height", barHeight)
                .classed(colorType === 'blue' ? "filled2" : colorType === 'red' ? "filled3" : "filled", (d, i) => { if (i < 1) return true; else return false; });

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
                .attr("y", (d, i) => y(i) + i * 10 - 4)
                .attr("dy", "1.1em")
                .attr("dx", 5)
                .attr("stroke", (d, i) => { if (i < 1) return colorType === 'blue' ? '#005ffc' : colorType === 'red' ? '#ff2a66' : '#00b9a1'; else return '#7d909f'; })
                .attr("stroke-width", "0.3")
                .attr("fill", (d, i) => { if (i < 1) return colorType === 'blue' ? '#005ffc' : colorType === 'red' ? '#ff2a66' : '#00b9a1'; else return '#7d909f'; })
                .text(d => format(d.value))

            // svg.append("g")
            //     .call(xAxis);

            // svg.append("g")
            //     .call(yAxis);
        }

        window.onload = () => {
            const data = {!! json_encode($data) !!};

            const testData = [
                { name: '건국대', value: 95 },
                { name: '참여대', value: 91 },
            ];
            handleHorizontalBarChart(data[0], '#par1');

            const testData2 = [
                { name: '건국대', value: 87 },
                { name: '참여대', value: 89 },
            ];
            handleHorizontalBarChart(data[1], '#par2');

            const testData3 = [
                { name: '건국대', value: 90 },
                { name: '참여대', value: 80 },
            ];
            handleHorizontalBarChart(data[2], '#par3', 'blue');

            const testData4 = [
                { name: '건국대', value: 48 },
                { name: '참여대', value: 80 },
            ];
            handleHorizontalBarChart(data[3], '#par4', 'red');

        }
    </script>
@endsection

@section('_content')
    <section class="content-wrap">
        <div class="content">
            <div class="col-wrap">
                <div class="content-card col col-6">
                    <h4 class="content-card__tit">평균 대비 만족도</h4>
                    <div class="graph-wrap p-t-0">
                        <div id="par1">
                            <h5 class="graph-tit__type2">전체 만족도 평균</h5>
                        </div>
                        <div id="par2">
                            <h5 class="graph-tit__type2">자기평가 점수</h5>
                        </div>
                    </div>
                </div>
                <div class="content-card col col-6">
                    <h4 class="content-card__tit">만족도 최고/최저 과목</h4>
                    <div class="graph-wrap p-t-0">
                        <div id="par3">
                            <h5 class="graph-tit__type2">{{ $data[4] }}@if($data[4] == '') &nbsp; @endif</h5>
                        </div>
                        <div id="par4">
                            <h5 class="graph-tit__type2">{{ $data[5] }}@if($data[5] == '') &nbsp; @endif</h5>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-wrap">
                <div class="content-card col col-5 h-360">
                    <span class="label label-green">세부내용</span>
                    <ul class="dot-list">
                        <li class="dot-list__item green">
                            자기평가 영역의 {{ Auth::user()->univName }} 평균은 {{ $data[1][0]['value'] }}점으로 참여대학 평균{{ $data[1][0]['value'] === $data[1][1]['value'] ? '과 같음' : ($data[1][0]['value'] > $data[1][1]['value'] ? '보다 높음' : '보다 낮음') }}
                        </li>
                        <li class="dot-list__item green">
                            [{{ $data[4] }}] 과목이 {{ $data[2][0]['value'] }}점으로 가장 높은 만족도를 보임
                        </li>
                        <li class="dot-list__item green">
                            [{{ $data[5] }}] 과목이 {{ $data[3][0]['value'] }}점으로 가장 낮은 만족도를 보임
                        </li>
                    </ul>
                </div>
                <div class="content-card col col-7 h-360">
                    <h4 class="content-card__tit">과목별 만족도</h4>
                    <table class="table t-center br-b m-t-20">
                        <colgroup>
                            <col width="70%" />
                            <col width="15%" />
                            <col width="15%" />
                        </colgroup>
                        <tr>
                            <th>참여과목 명</th>
                            <th>자기평가</th>
                            <th>참여대 평균</th>
                        </tr>
                        @if ($fourthTotals)
                            @foreach($fourthTotals->sortByDesc('selfEvaluationRate') as $fourthTotal)
                                <tr>
                                    <td>{{ $fourthTotal->lectureName }}</td>
                                    <td>{{ $fourthTotal->selfEvaluationRate }}</td>
                                    @if ($secondTotals)
                                        @foreach($secondTotals as $secondTotal)
                                            @if ($secondTotal->lectureName === $fourthTotal->lectureName)
                                            <td>{{ $secondTotal->selfEvaluationRate }}</td>
                                            @endif
                                        @endforeach
                                    @endif
                                </tr>
                            @endforeach
                        @endif
                    </table>
                </div>
            </div>
        </div>
    </section>
@endsection
