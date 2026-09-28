@extends('layouts.result')

@section('script')
    <script src="{{ asset('js/d3.js') }}"></script>

    <script>
        const handleDrawInlineGraph = (data, id) => {
            const min = d3.min(data, (d) => d3.min([d.value1, d.value2, d.value3]));
            const max = d3.max(data, (d) => d3.max([d.value1, d.value2, d.value3]));

            const height = 210;
            const width = 500;
            const margin = ({top: 30, right: 30, bottom: 35, left: 30});
            const labelPadding = 4;
            const series = data.columns.slice(2).map(key => data.map(({[key]: value, name1, name2}, i) => ({i, name1, name2, key, value})));
            // const z = d3.scaleOrdinal(data.columns.slice(2), d3.schemeCategory10);
            const z = (key) => {
                switch(key) {
                    case 'value1':
                        return '#ffe32a';
                        break;
                    case 'value2':
                        return '#616cec';
                        break;
                    case 'value3':
                        return '#ff2a66';
                        break;
                    default:
                        return 'black';
                }
            };
            const y = d3.scaleLinear()
                .domain([min - 10, max + 10])
                .range([height - margin.bottom, margin.top]);
            const x = d3.scaleUtc()
                .domain([0, 7])
                .range([margin.left, width - margin.right]);
            // const xAxis = g => g
            //     .attr("transform", `translate(0,${height - margin.bottom})`)
            //     .call(d3.axisBottom(x).ticks(width / 80).tickSizeOuter(0));

            // console.log(series);

            const svg = d3.select(id/*'svg'*//*'#graph'*/).append('svg')
                .attr("viewBox", [0, 0, width, height]);

            // svg.append("g")
            //     .call(xAxis);

            const line = d3.line()([[0, y(min-10)], [width, y(min-10)]]);
            // svg.append(line);

            svg.append("path")
                .attr("d", line)
                .attr("stroke", "#cfdbe5")

            // console.log(data);

            svg.append("g")
                .attr("fill", "#0c161e")
                .attr("stroke", "#0c161e")
                .attr("stroke-width", "0.1")
                .attr("text-anchor", "middle")
                .attr("font-family", "NanumSquare")
                .attr("font-size", 13)
                .selectAll("text")
                .data(data)
                .join("text")
                .attr("x", (d, i) => x(i))
                .attr("y", y(min - 10))
                .attr("dy", 14)
                .attr("dx", 0)
                .text(d => d.name1)
                .call(text => text.filter(d => x(d.value) - x(0) < 20) // short bars
                    .attr("dx", -80)
                    .attr("fill", "black")
                    .attr("text-anchor", "middle"));

            svg.append("g")
                .attr("fill", "#0c161e")
                .attr("stroke", "#0c161e")
                .attr("stroke-width", "0.1")
                .attr("text-anchor", "middle")
                .attr("font-family", "NanumSquare")
                .attr("font-size", 13)
                .selectAll("text")
                .data(data)
                .join("text")
                .attr("x", (d, i) => x(i))
                .attr("y", y(min - 10))
                .attr("dy", 28)
                .attr("dx", 0)
                .text(d => { if (d.hasOwnProperty('name2')) { return d.name2; } else return null; })
                .call(text => text.filter(d => x(d.value) - x(0) < 20) // short bars
                    .attr("dx", -80)
                    .attr("fill", "black")
                    .attr("text-anchor", "middle"));

            const serie = svg.append("g")
                .selectAll("g")
                .data(series)
                .join("g");

            serie.append("path")
                .attr("fill", "none")
                .attr("stroke", d => z(d[0].key))
                .attr("stroke-width", 1.5)
                .attr("d", d3.line()
                    .x(d => x(d.i))
                    .y(d => y(d.value)));

            // serie.append('circle')
            //     .attr('cx',-106.661513 )
            //     .attr('cy', 35.05917399 )
            //     .attr('r','10px')
            //     .style('fill', 'red');

            serie.append("g")
                .attr("stroke-linecap", "round")
                .attr("stroke-linejoin", "round")
                .attr("fill", "white")
                .selectAll("circle")
                .data(d => d)
                .join("circle")
                .attr('cx', d => x(d.i) )
                .attr('cy', d => y(d.value))
                .attr('r','3px')
                .on('mouseover', function (dom ,d) {
                    // console.log(this);

                    const circle = this.getBoundingClientRect();
                    // console.log(circle);

                    const tooltip = d3.select('#tooltip');
                    // console.log(tooltip.node().getBoundingClientRect());

                    tooltip
                        .attr('style', `
                        left: ${circle.x - 25}px;
                        top: ${circle.y + 15}px;
                        `)
                        .classed('graph-tooltip', true)
                        .html(`
                            <p>${data[d.i].value1}</p>
                            <p>${data[d.i].value2}</p>
                            <p>${data[d.i].value3}</p>
                        `)
                })
                .on('mouseout', () => {
                    d3.select('#tooltip')
                        .attr('style', `display: none;`)
                        .html(``)
                })
                .clone(true).lower()
                .attr("fill", "none")
                .attr("stroke", d => z(d.key))
                .attr("stroke-width", labelPadding)
                .on('mouseover', function (dom ,d) {
                    // console.log(this);

                    const circle = this.getBoundingClientRect();
                    // console.log(circle);

                    const tooltip = d3.select('#tooltip');
                    // console.log(tooltip.node().getBoundingClientRect());

                    tooltip
                        .attr('style', `
                        left: ${circle.x - 25}px;
                        top: ${circle.y + 15}px;
                        `)
                        .classed('graph-tooltip', true)
                        .html(`
                            <p>${data[d.i].value1}</p>
                            <p>${data[d.i].value2}</p>
                            <p>${data[d.i].value3}</p>
                        `)
                })
                .on('mouseout', () => {
                    d3.select('#tooltip')
                        .attr('style', `display: none;`)
                        .html(``)
                });

            // serie.append("g")
            //     .attr("font-family", "sans-serif")
            //     .attr("font-size", 10)
            //     .attr("stroke-linecap", "round")
            //     .attr("stroke-linejoin", "round")
            //     .attr("text-anchor", "middle")
            //     .selectAll("text")
            //     .data(d => d)
            //     .join("text")
            //     .text(d => d.value)
            //     .attr("dy", "0.35em")
            //     .attr("x", d => x(d.i))
            //     .attr("y", d => y(d.value))
            //     .call(text => text.filter((d, i, data) => i === data.length - 1)
            //         .append("tspan")
            //         .attr("font-weight", "bold")
            //         .text(d => ` ${d.key}`))
            //     .clone(true).lower()
            //     .attr("fill", "none")
            //     .attr("stroke", "white")
            //     .attr("stroke-width", labelPadding);
        }

        const handleVerticalBarChart = (data, id, colorType) => {
            const margin = ({top: 35, right: 0, bottom: 35, left: 0});

            const height = 250;
            const width = 230;

            const y = d3.scaleLinear()
                .domain([0, d3.max(data, d => d.value) > 100 ? d3.max(data, d => d.value) : 100]).nice()
                .range([height - margin.bottom, margin.top]);

            const x = d3.scaleBand()
                .domain(d3.range(data.length))
                .range([margin.left, width - margin.right])
                .padding(0.1);

            const svg = d3.select(id/*'svg'*//*'#graph'*/).append('svg')
                .attr("viewBox", [0, 0, width, height]);

            const svgDefs = svg.append('defs');
            const mainGradient = svgDefs.append('linearGradient')
                .attr('id', 'mainGradient')
                .attr('gradientTransform', 'rotate(90)');

            mainGradient.append('stop')
                .attr('class', 'stop-right')
                .attr('offset', '0');

            mainGradient.append('stop')
                .attr('class', 'stop-left')
                .attr('offset', '1');

            const mainGradient2 = svgDefs.append('linearGradient')
                .attr('id', 'mainGradient2')
                .attr('gradientTransform', 'rotate(90)');

            mainGradient2.append('stop')
                .attr('class', 'stop-right2')
                .attr('offset', '0');

            mainGradient2.append('stop')
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
                .attr("x", (d, i) => x(i) + (x.bandwidth() / 2) - 12)
                .attr("y", d => y(d.value))
                .attr("height", d => y(0) - y(d.value))
                .attr("width", 25)
                .classed(colorType === 'blue' ? "filled" : "filled2", true);

            svg.append("g")
                .attr("fill", "#0c161e")
                .attr("stroke", "#0c161e")
                .attr("stroke-width", "0.1")
                .attr("text-anchor", "middle")
                .attr("font-family", "NanumSquare")
                .attr("font-size", 13)
                .selectAll("text")
                .data(data)
                .join("text")
                .attr("x", (d, i) => x(i) + (x.bandwidth() / 2))
                .attr("y", y(0) + 10)
                .attr("dy", 8)
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
                .attr("font-size", 13)
                .selectAll("text")
                .data(data)
                .join("text")
                .attr("x", (d, i) => x(i) + (x.bandwidth() / 2))
                .attr("y", y(0))
                .attr("dy", 31)
                .attr("dx", 0)
                .text(d => { if (d.hasOwnProperty('name2')) { return d.name2; } else return null; })
                // .call(text => text.filter(d => x(d.value) - x(0) < 20) // short bars
                //     .attr("dx", -80)
                //     .attr("fill", "black")
                //     .attr("text-anchor", "middle"));

            svg.append("g")
                .attr("fill", colorType === 'blue' ? "#005ffc" : "#4949f7")
                .attr("stroke", colorType === 'blue' ? "#005ffc" : "#4949f7")
                .attr("stroke-width", "0.1")
                .attr("text-anchor", "middle")
                .attr("font-family", "NanumSquare")
                .attr("font-size", 13)
                .selectAll("text")
                .data(data)
                .join("text")
                .attr("x", (d, i) => x(i) + (x.bandwidth() / 2) + 13)
                .attr("y", (d, i) => y(d.value))
                .attr("dy", -5)
                .attr("dx", -12.5)
                .text(d => d.value)
                // .call(text => text.filter(d => x(d.value) - x(0) < 20) // short bars
                //     .attr("dx", -80)
                //     .attr("fill", colorType === 'blue' ? "#005ffc" : "#4949f7")
                //     .attr("text-anchor", "middle"));
        }

        const handleVerticalBarChart2 = (data, id, colorType) => {
            const margin = ({top: 45, right: 0, bottom: 35, left: 0});

            const height = 210;
            const width = 500;

            const y = d3.scaleLinear()
                .domain([0, 100]).nice()
                .range([height - margin.bottom, margin.top]);

            const x = d3.scaleBand()
                .domain(d3.range(data.length))
                .range([margin.left, width - margin.right])
                .padding(0.1);

            const svg = d3.select(id/*'svg'*//*'#graph'*/).append('svg')
                .attr("viewBox", [0, 0, width, height]);

            svg.append('line')
                .attr('x1', x(0))
                .attr('x2', x(data.length - 1) + x.bandwidth())
                .attr('y1', y(0))
                .attr('y2', y(0) + 0.5)
                .style('stroke', '#cfdbe5')
                .style('stroke-width', 1);

            svg.append("g")
                .attr("fill", '#ffe32a')
                .selectAll("rect")
                .data(data)
                .join("rect")
                .attr("x", (d, i) => x(i) + (x.bandwidth() / 2) - 30)
                .attr("y", d => y(d.value))
                .attr("height", d => y(0) - y(d.value))
                .attr("width", 20);

            svg.append("g")
                .attr("fill", colorType === 'green' ? '#73d44d' : '#ff932a')
                .selectAll("rect")
                .data(data)
                .join("rect")
                .attr("x", (d, i) => x(i) + (x.bandwidth() / 2) - 10)
                .attr("y", d => y(d.value2))
                .attr("height", d => y(0) - y(d.value2))
                .attr("width", 20);

            svg.append("g")
                .attr("fill", colorType === 'green' ? '#00a993' : '#ff2a66')
                .selectAll("rect")
                .data(data)
                .join("rect")
                .attr("x", (d, i) => x(i) + (x.bandwidth() / 2) + 10)
                .attr("y", d => y(d.value3))
                .attr("height", d => y(0) - y(d.value3))
                .attr("width", 20);

            svg.append("g")
                .attr("fill", "#0c161e")
                .attr("stroke", "#0c161e")
                .attr("stroke-width", "0.1")
                .attr("text-anchor", "middle")
                .attr("font-family", "NanumSquare")
                .attr("font-size", 13)
                .selectAll("text")
                .data(data)
                .join("text")
                .attr("x", (d, i) => x(i) + (x.bandwidth() / 2))
                .attr("y", y(0) + 10)
                .attr("dy", 8)
                .attr("dx", 0)
                .text(d => d.name)
                // .call(text => text.filter(d => x(d.value) - x(0) < 20) // short bars
                //     .attr("dx", -80)
                //     .attr("fill", "black")
                //     .attr("text-anchor", "middle"));

            svg.append("g")
                .attr("fill", "#e1c611")
                .attr("stroke", "#e1c611")
                .attr("stroke-width", "0.1")
                .attr("text-anchor", "middle")
                .attr("font-family", "NanumSquare")
                .attr("font-size", 13)
                .selectAll("text")
                .data(data)
                .join("text")
                .attr("x", (d, i) => x(i) + (x.bandwidth() / 2) - 8)
                .attr("y", (d, i) => y(d.value))
                .attr("dy", -5)
                .attr("dx", -12.5)
                .text(d => d.value)
                // .call(text => text.filter(d => x(d.value) - x(0) < 20) // short bars
                //     .attr("dx", -80)
                //     .attr("fill", "#e1c611")
                //     .attr("text-anchor", "middle"));

            svg.append("g")
                .attr("fill", colorType === 'green' ? "#73d44d" : "#ff932a")
                .attr("stroke", colorType === 'green' ? "#005ffc" : "#ff932a")
                .attr("stroke-width", "0.1")
                .attr("text-anchor", "middle")
                .attr("font-family", "NanumSquare")
                .attr("font-size", 13)
                .selectAll("text")
                .data(data)
                .join("text")
                .attr("x", (d, i) => x(i) + (x.bandwidth() / 2) + 13)
                .attr("y", (d, i) => y(d.value2))
                .attr("dy", -5)
                .attr("dx", -12.5)
                .text(d => d.value2)
                // .call(text => text.filter(d => x(d.value2) - x(0) < 20) // short bars
                //     .attr("dx", -80)
                //     .attr("fill", colorType === 'green' ? "#73d44d" : "#ff932a")
                //     .attr("text-anchor", "middle"));

            svg.append("g")
                .attr("fill", colorType === 'green' ? "#00a993" : "#ff2a66")
                .attr("stroke", colorType === 'green' ? "#00a993" : "#ff2a66")
                .attr("stroke-width", "0.1")
                .attr("text-anchor", "middle")
                .attr("font-family", "NanumSquare")
                .attr("font-size", 13)
                .selectAll("text")
                .data(data)
                .join("text")
                .attr("x", (d, i) => x(i) + (x.bandwidth() / 2) + 32)
                .attr("y", (d, i) => y(d.value3))
                .attr("dy", -5)
                .attr("dx", -12.5)
                .text(d => d.value3)
                // .call(text => text.filter(d => x(d.value3) - x(0) < 20) // short bars
                //     .attr("dx", -80)
                //     .attr("fill", colorType === 'blue' ? "#00a993" : "#ff2a66")
                //     .attr("text-anchor", "middle"));
        }

        window.onload = () => {
            const data = {!! json_encode($data) !!};


            // const _data = [
            //     { name1: '종합', name2: '만족도', value1: 86.5, value2: 76.5, value3: 66.5 },
            //     { name1: '자기', name2: '평가', value1: 81.0, value2: 79.3, value3: 65.5 },
            //     { name1: '강의', name2: '지원', value1: 87.5, value2: 76.5, value3: 70.5 },
            //     { name1: '내용/', name2: '교수법', value1: 78.5, value2: 84.5, value3: 80.3 },
            //     { name1: '학습', name2: '평가', value1: 67.5, value2: 76.5, value3: 86.5 },
            //     { name1: '교육', name2: '문명', value1: 84.5, value2: 76.5, value3: 62.5 },
            //     { name1: '시스템', name2: '', value1: 70.5, value2: 80.5, value3: 65.5 },
            //     { name1: '전반적', name2: '만족도', value1: 90.5, value2: 87.5, value3: 76.5 },
            // ];
            data[4]['columns'] = data[5]['columns'];
            // console.log(data[4]);

            handleDrawInlineGraph(data[4], '#result5');


            // const data2 = [
            //     { name: '2019', name2: '1학기', value: 301 },
            //     { name: '2019', name2: '2학기', value: 202 },
            //     { name: '2020', name2: '1학기', value: 400 },
            // ];
            handleVerticalBarChart(data[0], '#result1', 'blue');

            // const data3 = [
            //     { name: '2019', name2: '1학기', value: 32 },
            //     { name: '2019', name2: '2학기', value: 22 },
            //     { name: '2020', name2: '1학기', value: 44 },
            // ];
            handleVerticalBarChart(data[1], '#result2', 'purple');
            console.log(data[1]);

            // const data4 = [
            //     { name: '출석률', value: 75, value2: 50, value3: 99 },
            //     { name: '학습진도율', value: 75, value2: 50, value3: 99 },
            //     { name: '수료율', value: 75, value2: 50, value3: 99 },
            //     { name: '결석률', value: 75, value2: 50, value3: 99 },
            // ];
            handleVerticalBarChart2(data[2], '#result3', 'green');

            // const data5 = [
            //     { name: '시험응시율', value: 75, value2: 50, value3: 99 },
            //     { name: '과제수행률', value: 75, value2: 50, value3: 99 },
            //     { name: '토론수행률', value: 75, value2: 50, value3: 99 },
            //     { name: '퀴즈수행률', value: 75, value2: 50, value3: 99 },
            //     { name: '학습참여도', value: 75, value2: 50, value3: 99 },
            // ];
            handleVerticalBarChart2(data[3], '#result4', 'red');
        }
    </script>
@endsection

@section('_content')
    <section class="content-wrap">
        <div class="content">
            <div class="col-wrap">
                <div class="content-card col col-12">
                    <h4 class="content-card__tit">전년도/직전 학기 대비</h4>
                    <div class="col-wrap">
                        <div class="col col-6 col-wrap">
                            <div class="col col-6 pr p-b-30" id="result1">
                                <label class="graph-label">수강생 수 현황</label>
                            </div>
                            <div class="col col-6 pr p-b-30" id="result2">
                                <label class="graph-label">과목 수 현황</label>
                            </div>
                        </div>
                        <div class="col col-6 pr p-b-30" id="result3">
                            <div class="graph-div">
                                <div class="graph-div__left">
                                    <span class="circle-sm circle-yellow"></span>{{ $__year }} {{ $__month }}학기
                                    <span class="circle-sm circle-green2 m-l-10"></span>{{ $_year }} {{ $_month }}학기
                                    <span class="circle-sm circle-green3 m-l-10"></span>{{ $year }} {{ $month }}학기
                                </div>
                                <div class="graph-div__right">
                                    (단위 : %)
                                </div>
                            </div>
                            <label class="graph-label">과정이수 현황</label>
                        </div>
                    </div>
                    <div class="col-wrap m-t-20">
                        <div class="col col-6 pr p-b-30" id="result4">
                            <div class="graph-div">
                                <div class="graph-div__left">
                                    <span class="circle-sm circle-yellow"></span>{{ $__year }} {{ $__month }}학기
                                    <span class="circle-sm circle-orange m-l-10"></span>{{ $_year }} {{ $_month }}학기
                                    <span class="circle-sm circle-red m-l-10"></span>{{ $year }} {{ $month }}학기
                                </div>
                                <div class="graph-div__right">
                                    (단위 : %)
                                </div>
                            </div>
                            <label class="graph-label">학습참여 현황</label>
                        </div>
                        <div class="col col-6 pr p-b-30" id="result5">
                            <span id="tooltip" style="display: none"></span>
                            <div class="graph-div">
                                <div class="graph-div__left">
                                    <span class="circle-sm circle-yellow__line"></span>{{ $__year }} {{ $__month }}학기
                                    <span class="circle-sm circle-purple__line m-l-10"></span>{{ $_year }} {{ $_month }}학기
                                    <span class="circle-sm circle-red__line m-l-10"></span>{{ $year }} {{ $month }}학기
                                </div>
                                <div class="graph-div__right">
                                    (단위 : %)
                                </div>
                            </div>
                            <label class="graph-label">만족도 현황</label>
                        </div>
                    </div>
                    <div class="line-div m-t-40"></div>
                    <ul class="check-list">
                        @if($firstPer && $firstPer->content)
                            @foreach($firstPer->content as $content)
                            <li class="check-list__item">
                                {{ $content }}
                            </li>
                            @endforeach
                        @endif
                    </ul>
                </div>
            </div>
        </div>
    </section>
@endsection
