@extends('layouts.register')

@section('content')

<div class="container">

    <form method="post" action="/classes">
        <select name="section" id="section" required>
            <option value="acc">Accounting</option>
            <option value="ado">Adolescence Education</option>
            <option value="asl">American Sign Language</option>
            <option value="ams">American Studies</option>
            <option value="ant">Anthropology</option>
            <option value="ara">Arabic</option>
            <option value="art">Art</option>
            <option value="aed">Art Education</option>
            <option value="arh">Art History</option>
            <option value="ast">Astronomy</option>
            <option value="bfr">Behavioral Forensics</option>
            <option value="bio">Biology</option>
            <option value="bhi">Biomedical/Health Informatics</option>
            <option value="brc">Broadcasting</option>
            <option value="blw">Business Law</option>
            <option value="che">Chemistry</option>
            <option value="ced">Childhood Education</option>
            <option value="chi">Chinese</option>
            <option value="css">Cinema And Screen Studies</option>
            <option value="cog">Cognitive Science</option>
            <option value="cas">College Of Arts And Sciences</option>
            <option value="cma">Comm Media Arts</option>
            <option value="com">Communication</option>
            <option value="csc">Computer Science</option>
            <option value="cps">Counseling & Psychological Srv</option>
            <option value="crw">Creative Writing</option>
            <option value="dnc">Dance</option>
            <option value="dasa">Dasa Training</option>
            <option value="eco">Economics</option>
            <option value="edu">Education</option>
            <option value="ead">Education Administration</option>
            <option value="ece">Electrical Computer Engineer</option>
            <option value="eng">English</option>
            <option value="fin">Finance</option>
            <option value="fre">French</option>
            <option value="wst">Gender & Womens Studies</option>
            <option value="gst">General Studies</option>
            <option value="geg">Geography</option>
            <option value="geo">Geology</option>
            <option value="ger">German</option>
            <option value="grt">Gerontology</option>
            <option value="gsl">Global & International Studies</option>
            <option value="hsc">Health Science</option>
            <option value="his">History</option>
            <option value="hon">Honors Program</option>
            <option value="hci">Human Computer Interaction</option>
            <option value="hdv">Human Development</option>
            <option value="hrm">Human Resource Management</option>
            <option value="isc">Information Science</option>
            <option value="ist">International Studies</option>
            <option value="int">Interpretation</option>
            <option value="ita">Italian</option>
            <option value="jpn">Japanese</option>
            <option value="jlm">Journalism</option>
            <option value="lin">Linguistics</option>
            <option value="lit">Literacy Education</option>
            <option value="mgt">Management</option>
            <option value="mkt">Marketing</option>
            <option value="mba">Master Business Administration</option>
            <option value="mat">Mathematics</option>
            <option value="max">Mathematics- Remedial</option>
            <option value="met">Meteorology</option>
            <option value="msc">Military Science</option>
            <option value="mus">Music</option>
            <option value="nas">Native American Studies</option>
            <option value="oce">Oceanography</option>
            <option value="phl">Philosophy</option>
            <option value="ped">Physical Education</option>
            <option value="phy">Physics</option>
            <option value="pol">Political Science</option>
            <option value="por">Portuguese</option>
            <option value="psy">Psychology</option>
            <option value="pbj">Public Justice</option>
            <option value="rmi">Risk Management And Insurance</option>
            <option value="sshs">Safe Schools Healthy Students</option>
            <option value="soc">Sociology</option>
            <option value="spa">Spanish</option>
            <option value="spe">Special Education</option>
            <option value="sus">Sustainability</option>
            <option value="tsl">TESOL Education</option>
            <option value="tel">Technology</option>
            <option value="ted">Technology Education</option>
            <option value="tht">Theatre</option>
            <option value="vtp">Vocational Teacher Preparation</option>
            <option value="zoo">Zoology</option>
        </select>
        <button type="submit" class="class" value="submit">Submit</button>
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
    </form>
</div>
@endsection
