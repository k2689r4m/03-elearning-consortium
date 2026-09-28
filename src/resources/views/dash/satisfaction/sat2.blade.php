@extends('layouts.sat')

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
            const width = 475;
            // const height = Math.ceil((data.length + .4) * barHeight) + margin.top + margin.bottom;
            const height = 200;

            // const yAxis = g => g
            //     .attr("transform", `translate(${margin.left},0)`)
            //     .call(d3.axisLeft(y).tickFormat(i => data[i].name).tickSizeOuter(0));

            const x = d3.scaleLinear()
                .domain([min, 100])
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
                .attr('class', 'stop-left4')
                .attr('offset', '0');

            mainGradient.append('stop')
                .attr('class', 'stop-right4')
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
                .attr("x", d => min === 0 ? x(min) : x(min))
                .attr("y", (d, i) => y(i) + i * 10 - 4)
                .attr("dy", d => { if (d.hasOwnProperty('name2')) { return ".75em"; } else return "1.1em"; })
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
                .attr("x", d => x(min))
                .attr("y", (d, i) => y(i) + i * 10 - 4)
                .attr("dy", "2.3em")
                .attr("dx", -10)
                .text(d => { if (d.hasOwnProperty('name2')) { return d.name2; } else return null; })

            svg.append("g")
                .attr("fill", '#cfdbe5')
                .selectAll("rect")
                .data(data)
                .join("rect")
                .attr("x", x(min))
                .attr("y", (d, i) => y(i) + i * 10 - 4)
                .attr("width", d => x(d.value) - x(min))
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
                .attr("y", (d, i) => y(i) + i * 10 - 4)
                .attr("dy", "1.1em")
                .attr("dx", 5)
                .attr("stroke", (d, i) => { if (i < 1) return '#00b9a1'; else return '#7d909f'; })
                .attr("stroke-width", "0.3")
                .attr("fill", (d, i) => { if (i < 1) return '#00b9a1'; else return '#7d909f'; })
                .text(d => format(d.value))

            // svg.append("g")
            //     .call(xAxis);

            // svg.append("g")
            //     .call(yAxis);
        }

        function handleHorizontalBarChart2(data, id) {

            let min = d3.min(data, d => d.value);
            min = (Math.floor(min / 10)) * 10;

            if (min > 5) {
                min -= 10;
            }

            let max = d3.max(data, d => d.value);

            max = (Math.floor(max / 10)) * 10;

            if (max <= 90) {
                max += 20;
            }

            //95

            const maxValue = max;//Math.ceil(d3.max(data, d => d.value)/10)*10;

            const margin = ({top: 20, right: (maxValue.toString().length < 1 ? 1 : maxValue.toString().length) * 11, bottom: 20, left: 110})
            const barHeight = 16;
            const width = 470;
            // const height = Math.ceil((data.length + .4) * barHeight) + margin.top + margin.bottom;
            const height = 520;

            // const yAxis = g => g
            //     .attr("transform", `translate(${margin.left},0)`)
            //     .call(d3.axisLeft(y).tickFormat(i => data[i].name).tickSizeOuter(0));

            const x = d3.scaleLinear()
                .domain([min, max])
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
                    .ticks(5)
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
                .attr("x", d => x(min))
                .attr("y", (d, i) => y(i) + y.bandwidth() / 2 - barHeight / 2 - 2)
                .attr("dy", "1.1em")
                .attr("dx", -10)
                .text(d => d.name)

            svg.append("g")
                .attr("fill", '#cfdbe5')
                .selectAll("rect")
                .data(data)
                .join("rect")
                .attr("x", x(min))
                .attr("y", (d, i) => y(i) + y.bandwidth() / 3 - barHeight / 2 + 4)
                .attr("width", d => x(d.value) - x(min))
                .attr("height", barHeight)
                .classed("filled", true);

            svg.append("g")
                .attr("fill", '#cfdbe5')
                .selectAll("rect")
                .data(data)
                .join("rect")
                .attr("x", x(min))
                .attr("y", (d, i) => y(i) + y.bandwidth() - barHeight * 2 + 4)
                .attr("width", d => x(d.value2) - x(min))
                .attr("height", barHeight);

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
                .attr("y", (d, i) => y(i) + y.bandwidth() / 3 - barHeight / 2)
                .attr("dy", "1.1em")
                .attr("dx", 5)
                .attr("stroke", '#00b9a1')
                .attr("stroke-width", "0.3")
                .attr("fill", '#00b9a1')
                .text(d => format(d.value))

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
                .attr("x", d => x(d.value2))
                .attr("y", (d, i) => y(i) + y.bandwidth() / 3 - barHeight / 2)
                .attr("dy", "2.2em")
                .attr("dx", 5)
                .attr("stroke", '#7d909f')
                .attr("stroke-width", "0.3")
                .attr("fill", '#7d909f')
                .text(d => format(d.value2))

            // svg.append("g")
            //     .call(xAxis);

            // svg.append("g")
            //     .call(yAxis);
        }

        function handleDonutChart (data, id, colorType)  {

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
            const mainGradient2 = svgDefs.append('linearGradient')
                .attr('id', 'mainGradient2');
            const mainGradient3 = svgDefs.append('linearGradient')
                .attr('id', 'mainGradient3');
            // .attr('gradientTransform', 'rotate(45)');

            mainGradient2.append('stop')
                .attr('class', 'stop-left')
                .attr('offset', '0');

            mainGradient2.append('stop')
                .attr('class', 'stop-right')
                .attr('offset', '1');

            mainGradient3.append('stop')
                .attr('class', 'stop-left3')
                .attr('offset', '0');

            mainGradient3.append('stop')
                .attr('class', 'stop-right3')
                .attr('offset', '1');

            svg.append('text').style('font-size', '18px').style('fill', colorType === 'blue' ? '#005ffc' : '#ff2a66').style('font-weight','bold')
                .attr('text-anchor', 'middle').attr('transform', 'translate(0, 5)')
                .text(data[0].value.toFixed(0) + '점');

            svg.append('g').selectAll('path')
                .data(arcs)
                .enter().append('path')
                .classed('filled', function (d, i) { if (d.name === 'true') return true; else return false; } )
                .attr('fill', function(d, i) { return d.data.name === 'true' ? colorType === "blue" ? "url(#mainGradient2)" : "url(#mainGradient3)" : falseColor })
                .attr('d', arc)
        }

        window.onload = () => {
            const data = {!! json_encode($data) !!};

            handleHorizontalBarChart(data[0], '#par1');

            handleDonutChart(data[1], '#par2', 'blue');

            handleDonutChart(data[3], '#par3', 'red');

            handleHorizontalBarChart2(data[5], '#comp_sta');
        }
    </script>
@endsection

@section('_content')
    <section class="content-wrap">
        <div class="content">
            <div class="col-wrap">
                <div class="content-card col col-6 h-750">
                    <h4 class="content-card__tit">만족도 평균</h4>
                    <div id="par1"></div>
                    <p class="fs-md t-center p-t-20">만족도 평균 <strong class="fc-green">{{ $data[0][0]['value'] }}</strong>점</p>
                    <div class="graph-wrap col-wrap p-t-0">
                        <div class="col col-6 p-40 pr" id="par2">
                            <div class="graph-count row-2">
                                <span class="w-130">만족도 최고 과목</span>
                                <div class="line"></div>
                                <span class="fc-blue">{{ $data[2] }}</span>
                            </div>
                        </div>

                        <div class="col col-6 p-40 pr" id="par3">
                            <div class="graph-count row-2">
                                <span class="w-130">만족도 최저 과목</span>
                                <div class="line"></div>
                                <span class="fc-red">{{ $data[4] }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="line-div"></div>

                    <ul class="dot-list m-b-0">
                        <li class="dot-list__item green">
                            [{{ $data[2] }}]과목의 만족도가 {{ $data[1][0]['value'] }}점으로 가장 높게 나타남
                        </li>
                        <li class="dot-list__item green">
                            [{{ $data[4] }}]과목의 만족도가 {{ $data[3][0]['value'] }}점으로 가장 낮게 나타남
                        </li>
                    </ul>
                </div>
                <div class="content-card col col-6 h-750">
                    <h4 class="content-card__tit">항목별 만족도</h4>
                    <div class="graph-div">
                        <div class="graph-div__left">
                            <span class="circle-sm circle-green"></span>{{ auth()->user()->univName }}<br>
                            <span class="circle-sm circle-gray"></span>참여대학평균<br>
                            (단위 : %)
                        </div>
                    </div>
                    <div id="comp_sta"></div>
                </div>
            </div>
        </div>
    </section>
@endsection
